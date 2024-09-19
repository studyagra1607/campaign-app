<?php

namespace App\Jobs;

use App\Events\UserEvent;
use App\Http\Traits\CsvParser;
use App\Models\Email;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Services\UserLogService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadEmailCsvToDBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CsvParser;

    protected  $data;
    
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
        $category_ids = $this->data['category_ids'];
        $filename = $this->data['file_name'];
        
        $logService = new UserLogService($userId);

        $logService->logForUser(PHP_EOL . PHP_EOL);
        
        $logService->logForUser("User {$userId} Started: emails save to DB at " . now()->format('d-m-y H:i:s') . ".");
        
        if (Storage::disk('local')->exists($this->data['file_path'])) {

            $file = Storage::disk('local')->get($this->data['file_path']);

            $data = collect($this->CsvToArray($file, true));
            
            $logService->logForUser("Collect csv data from uploaded file!");
            
            $data->chunk(100)->each(function ($chunk) use (&$userId, &$category_ids) {
                foreach ($chunk as $key => $row)
                {

                    $emailRecord = Email::updateOrCreate(
                        [
                            'email' => $row['email'],
                            'user_id' => $userId,
                        ],
                        [
                            'name' => $row['name'],
                        ]
                    );

                    $emailRecord->categories()->sync($category_ids);
                    
                };
            });
            
            $logService->logForUser("User {$userId} Completed: emails save to DB at " . now()->format('d-m-y H:i:s') . ".");
            
        }else{

            $logService->logForUser('Uploaded csv file not found!');

            $logService->logForUser("User {$userId} Failed: emails save to DB at " . now()->format('d-m-y H:i:s') . ".");
            
            $logService->logForUser(PHP_EOL . PHP_EOL);
            
            $msg = "<b>$filename</b> upload to DB failed!";
            $temp = $msg . "<ul><li>Uploaded csv file not found!</li></ul>";

            $user = User::find($userId);
            $user->notify(new UserNotification('error', $temp));
            
            event(new UserEvent($userId, 'csv-upload-to-db', ['type' => 'error', 'msg' => $msg]));
            
            return;
            
        };
        
        $msg = "<b>$filename</b> uploaded to DB successfully!";
            
        $user = User::find($userId);
        $user->notify(new UserNotification('info', $msg));
        
        event(new UserEvent($userId, 'csv-upload-to-db', ['type' => 'success', 'msg' => $msg]));
        
        $logService->logForUser(PHP_EOL . PHP_EOL);
        
    }

    public function failed(Exception $exception)
    {
        $userId = $this->data['user_id'];
        $filename = $this->data['file_name'];

        $logService = new UserLogService($userId);
        $logService->logForUser(PHP_EOL . PHP_EOL);
        $logService->logForUser("Job failed: " . class_basename($this));
        $logService->logForUser("Reason message: " . $exception->getMessage());
        $logService->logForUser(PHP_EOL . PHP_EOL);

        $msg = "<b>$filename</b> upload to DB failed!";
        $temp = $msg . "<ul><li>".$exception->getMessage()."</li></ul>";  

        $user = User::find($userId);
        $user->notify(new UserNotification('error', $temp));
        
        event(new UserEvent($userId, 'csv-upload-to-db', ['type' => 'error', 'msg' => $msg]));
    }
}
