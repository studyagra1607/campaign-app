<?php

namespace App\Jobs;

use App\Http\Traits\CsvParser;
use App\Models\Email;
use App\Services\UserLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        
        $logService = new UserLogService($userId);

        $logService->logForUser(PHP_EOL . PHP_EOL);
        
        $logService->logForUser("User {$userId} Started: emails save to DB at " . now()->format('d-m-y H:i:s') . ".");
        
        if (Storage::disk('local')->exists($this->data['file_path'])) {

            $file = Storage::disk('local')->get($this->data['file_path']);

            $data = collect($this->CsvToArray($file, true));
            
            $logService->logForUser("Collect csv data from uploaded file!");
            
            $data->chunk(100, function ($chunk) use ($userId, $category_ids) {
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
            
        };
        
        $logService->logForUser(PHP_EOL . PHP_EOL);
        
    }
}
