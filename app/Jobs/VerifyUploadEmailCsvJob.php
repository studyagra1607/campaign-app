<?php

namespace App\Jobs;

use App\Http\Traits\CsvParser;
use App\Services\UserLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VerifyUploadEmailCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CsvParser;

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
        $csvErrors = [];
        
        $userId = $this->data['user_id'];

        $logService = new UserLogService($userId);

        $logService->logForUser(PHP_EOL . PHP_EOL);
        
        $logService->logForUser("User {$userId} Started: emails uploading at " . now()->format('d-m-y H:i:s') . ".");

        $logService->logForUser("Received data form user: ", $this->data);
        
        if (Storage::disk('local')->exists($this->data['file_path'])) {

            $file = Storage::disk('local')->get($this->data['file_path']);

            $data = collect($this->CsvToArray($file, true));
            
            $logService->logForUser("Collect csv data from uploaded file!");
            
            $logService->logForUser("Csv verification start at " . now()->format('d-m-y H:i:s') . ".");
            
            $data->chunk(100, function ($chunk) use (&$csvErrors, $logService) {
                foreach ($chunk as $key => $row)
                {

                    $validator = Validator::make($row, config('constant.email_csv_rules'));
                    
                    if ($validator->fails()) {

                        $collect = [
                            $key + 2,
                            json_encode($row),
                            json_encode($validator->errors()->toArray()),
                        ];
                        
                        array_push($csvErrors, $collect);

                        $logService->logForUser('Csv Validation Error on line no. '.$key+2, $validator->errors()->toArray());
                        
                    };

                };
            });

            $logService->logForUser("Csv verification end at " . now()->format('d-m-y H:i:s') . ".");
            
        }else{

            $logService->logForUser('Uploaded csv file not found!');

        };

        if(!empty($csvErrors)){
            $headers = [
                'Line No.',
                'Data',
                'Errors',
            ];

            array_unshift($csvErrors, $headers);
            $csvErrorsFile = $this->arrayToCsv($csvErrors);
            
            $filename = $this->data['file_name'];
            $slugname = Str::slug(pathinfo($filename, PATHINFO_FILENAME));
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $folder_name = 'csv-errors';
            $folder_id = $this->data['user_id'];
            $file_slug = time().'-'.$slugname.'.'.$extension;
            $full_filepath = "{$folder_name}/{$folder_id}/{$file_slug}";

            Storage::disk('public')->put($full_filepath, $csvErrorsFile);

            $logService->logForUser("Csv errors file saved successfully on: $full_filepath");

            $logService->logForUser("User {$userId} Failed: emails csv verification at " . now()->format('d-m-y H:i:s') . ".");
            
        }else{

            $logService->logForUser("User {$userId} Completed: emails csv verification at " . now()->format('d-m-y H:i:s') . ".");

            sleep(3);
            
            dispatch(new UploadEmailCsvToDBJob($this->data));
            
        };
        
        $logService->logForUser(PHP_EOL . PHP_EOL);
        
    }
}
