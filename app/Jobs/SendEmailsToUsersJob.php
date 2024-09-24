<?php

namespace App\Jobs;

use App\Events\UserEvent;
use App\Http\Traits\CsvParser;
use App\Mail\CampaignEmail;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Email;
use App\Models\Template;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\UserLogService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailsToUsersJob implements ShouldQueue
{
    use CsvParser, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $userId = $this->data['user_id'];

        $logService = new UserLogService($userId);

        $logService->logForUser(PHP_EOL.PHP_EOL);

        $logService->logForUser("User {$userId} Ran: campaign - '{$this->data['name']}' at ".now()->format('d-m-y H:i:s').'.');

        $logService->logForUser('Received campaign information form user: ', $this->data->toArray());

        $campaign_id = $this->data['id'];
        $category_id = $this->data['category_id'];
        $template_id = $this->data['template_id'];

        $campaign = Campaign::where('id', $campaign_id)->where('user_id', $userId);
        $category = Category::where('id', $category_id)->where('user_id', $userId);
        $template = Template::where('id', $template_id)->where('user_id', $userId);

        $ErrorsOrTemplate = $this->getErrorsOrTemplate($logService, $campaign, $category, $template);

        if (! empty($ErrorsOrTemplate['errors'])) {
            $this->fail(new \Exception(json_encode($ErrorsOrTemplate['errors'])));

            return;
        }

        $template = $ErrorsOrTemplate['template'];

        $emailsRecoders = Email::active()
            ->subscribe()
            ->where('user_id', $userId)
            ->whereHas('categories', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            })
            ->select('name', 'email');

        $emailsCount = $emailsRecoders->count();

        $category = $category->first();

        $emails_status = [];

        $logService->logForUser('Get emails from DB in chunk!');

        $emailsRecoders->chunk(100, function ($chunk) use (&$logService, &$category, &$template, &$emails_status) {
            foreach ($chunk as $email) {
                $status = null;
                try {
                    $data = [];
                    $data['username'] = $email->name;
                    $email_template = updateDataToTemplate($data, $template);

                    Mail::to($email->email)->send(new CampaignEmail($category->name, $email_template));

                    $status = 'send';
                    $logService->logForUser('Mail send successfully to '.$email->email.'!');
                } catch (\Exception $e) {
                    $status = 'failed';
                    $logService->logForUser('Failed mail send to '.$email->email.'!', $e->getMessage());
                }
                $collect = [
                    $email->name,
                    $email->email,
                    $status,
                ];
                array_push($emails_status, $collect);
            }
        });

        $fileInfo = $this->createEmailsStatusCsv($logService, $emails_status);

        $msg = "<b>{$this->data['name']}</b> campaign completed successfully! ";
        $temp = $msg." <a href='".config('app.url')."/storage/{$fileInfo['full_filepath']}'>view status</a>";

        $logService->logForUser($temp);

        $logService->logForUser(PHP_EOL.PHP_EOL);

        $campaign->progress('complete');

        $user = User::find($userId);
        $user->notify(new UserNotification('success', $temp));

        event(new UserEvent($userId, 'campaign', ['type' => 'success', 'msg' => $msg, 'campaign_id' => $campaign_id]));

    }

    protected function getErrorsOrTemplate($logService, $campaign, $category, $template)
    {
        $models = [
            'Campaign' => $campaign,
            'Category' => $category,
            'Template' => $template,
        ];

        $errors = [];
        $template_file = null;

        foreach ($models as $modelName => $modelInfo) {
            $model = $modelInfo->first();

            $exists = $modelInfo->exists();
            $active = $modelName !== 'Template' ? $modelInfo->active()->exists() : true;

            if ($modelName === 'Template' && $exists) {
                if (Storage::disk('local')->exists($model->file_path)) {
                    $template_file = Storage::disk('local')->get($model->file_path);
                } else {
                    $msg = 'Template file not found!';
                    $errors[] = $msg;
                    $logService->logForUser($msg);
                }
            }

            if (! $exists) {
                $msg = "Selected {$modelName} does not exist!";
                $errors[] = $msg;
                $logService->logForUser($msg);
            }

            if ($exists && ! $active) {
                $msg = "{$modelName} <b>{$model->name}</b> is not active!";
                $errors[] = $msg;
                $logService->logForUser($msg);
            }
        }

        return [
            'errors' => $errors,
            'template' => $template_file,
        ];
    }

    protected function createEmailsStatusCsv($logService, $emails_status)
    {
        $headers = [
            'Name',
            'Email',
            'Status',
        ];

        array_unshift($emails_status, $headers);
        $emails_status_file = $this->arrayToCsv($emails_status);

        $folder_name = 'campaign-emails-status';
        $folder_id = $this->data['user_id'];
        $file_slug = time().'-'.'emails-status.csv';
        $full_filepath = "{$folder_name}/{$folder_id}/{$file_slug}";

        Storage::disk('public')->put($full_filepath, $emails_status_file);

        $logService->logForUser("Emails status file saved successfully on: $full_filepath");

        return [
            'file_slug' => $file_slug,
            'full_filepath' => $full_filepath,
        ];
    }

    public function failed(Exception $exception)
    {
        $userId = $this->data['user_id'];
        $campaign_id = $this->data['id'];

        $logService = new UserLogService($userId);
        $logService->logForUser(PHP_EOL.PHP_EOL);

        $pmsg = "<b>{$this->data['name']}</b> campaign failed!";
        $msg = $exception->getMessage();

        if (is_array(json_decode($msg))) {
            $msgs = json_decode($msg);
            $temp = $pmsg.'<ul>';
            foreach ($msgs as $msg) {
                $temp .= "<li>$msg</li>";
            }
            $temp .= '</ul>';
            $msg = 'get not active or not exists errors!';
        } else {
            $temp = $pmsg."<ul><li>$msg</li></ul>";
        }

        $logService->logForUser('Job failed: '.class_basename($this));
        $logService->logForUser('Reason message: '.$msg);
        $logService->logForUser(PHP_EOL.PHP_EOL);

        Campaign::where('id', $campaign_id)->where('user_id', $userId)->progress('failed');

        $user = User::find($userId);
        $user->notify(new UserNotification('error', $temp));

        event(new UserEvent($userId, 'campaign', ['type' => 'error', 'msg' => $pmsg, 'campaign_id' => $campaign_id]));

    }
}
