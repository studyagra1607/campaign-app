<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Email;
use App\Models\Template;
use App\Services\UserLogService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendEmailsToUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

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

        $logService->logForUser(PHP_EOL . PHP_EOL);

        $logService->logForUser("User {$userId} Ran: campaign - '{$this->data['name']}' at " . now()->format('d-m-y H:i:s') . ".");

        $logService->logForUser("Received campaign information form user: ", $this->data->toArray());        
        
        $campaign_id = $this->data['id'];
        $category_id = $this->data['category_id'];
        $template_id = $this->data['template_id'];

        $campaign = Campaign::where('id', $campaign_id)->where('user_id', $userId);
        $category = Category::where('id', $category_id)->where('user_id', $userId);
        $template = Template::where('id', $template_id)->where('user_id', $userId);
        
        $models = [
            'Campaign' => $campaign,
            'Category' => $category,
            'Template' => $template,
        ];

        foreach ($models as $modelName => $modelInfo) {
            $model = $modelInfo->first();
            
            $exists = true;
            $active = true;
            
            $exists = $modelInfo->exists();
            
            if($modelName != 'Template'){
                $active = $modelInfo->active()->exists();
            }else{
                if (Storage::disk('local')->exists($model->file_path)){
                    $template = Storage::disk('local')->get($model->file_path);
                }else{
                    $logService->logForUser('Template file not found!');
                };
            }
            
            if (!$exists) {
                $logService->logForUser("{$modelName} '{$model->name}' does not exist!");
            }

            if (!$active) {
                $logService->logForUser("{$modelName} '{$model->name}' is not active!");
            }
        };
        
        $emailsRecoders = Email::active()
            ->subscribe()
            ->where('user_id', $userId)
            ->whereHas('categories', function ($query) use ($category_id) {
                $query->where('id', $category_id);
            })
            ->select('name', 'email');

        $emailsCount = $emailsRecoders->count();
            
        $category = $category->first(); 

        $logService->logForUser("Get emails from DB in chunk!");
        
        $emailsRecoders->chunk(100, function ($chunk) use (&$logService, &$category, &$template) {
            foreach ($chunk as $email) {
                try {
                    $data = [];
                    $data['username'] = $email->name;
                    $template = updateDataToTemplate($data, $template);

                    // Mail::queue(function ($mail) use ($category, $email, $template) {
                    //     $mail->to($email->email)->subject($category->name)->html($template);
                    // });
                    
                    $logService->logForUser("Mail send successfully to " . $email->email . "!");
                } catch (\Exception $e) {
                    $logService->logForUser("Failed to send email: " . $e->getMessage());
                };
                // sleep(1);
            }
        });
        
        $logService->logForUser(PHP_EOL . PHP_EOL);
    }

    public function failed(Exception $exception)
    {
        $logService = new UserLogService($this->data['user_id']);
        $logService->logForUser(PHP_EOL . PHP_EOL);
        $logService->logForUser("Job failed: " . class_basename($this));
        $logService->logForUser("Reason message: " . $exception->getMessage());
        $logService->logForUser(PHP_EOL . PHP_EOL);
    }
}
