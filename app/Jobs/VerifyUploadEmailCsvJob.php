<?php

namespace App\Jobs;

use App\Http\Traits\CsvParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        
        $errors = [
            [
                'Line No.',
                'Data',
                'Errors',
            ],
        ];

        $state = false;
        
        Log::info('UploadEmailCsvJob started : '. now()->format('d-m-y H:i:s'), $this->data);
        
        Log::info("UploadEmailCsvJob file : {$this->data['file_path']}");
        
        if (Storage::disk('local')->exists($this->data['file_path'])) {

            $file = Storage::disk('local')->get($this->data['file_path']);

            $data = collect($this->CsvToArray($file, true));
            
            Log::info("Collect Csv data from file!");
            
            $data->chunk(100)->each(function ($chunk) use (&$errors, &$state) {
                foreach ($chunk as $key => $row)
                {

                    $validator = Validator::make($row, config('constant.email_csv_rules'));
                    
                    if ($validator->fails()) {

                        $state = true;

                        Log::error('Csv Validation Error: ', $validator->errors()->toArray());

                        $collect = [
                            $key + 2,
                            json_encode($row),
                            json_encode($validator->errors()->toArray()),
                        ];
                        
                        array_push($errors, $collect);

                    };

                };
            });

        }else{

            Log::error('File not found!');

        };

        Log::info('UploadEmailCsvJob ended : '. now()->format('d-m-y H:i:s'), $this->data);

        Log::info('All Csv Errors: '. $this->arrayToCsv($errors));

    }
}
