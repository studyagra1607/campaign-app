<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class UserLogService
{
    
    protected $userId;
    protected $level;
    protected $logger;

    public function __construct($userId, $level = 'info')
    {
        $this->userId = $userId;
        $this->level = $level;

        $directory = storage_path("app/local/{$userId}/logs");

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = "{$directory}/user.log";

        $this->logger = Log::build([
            'driver' => 'single',
            'path' => $path,
            'level' => $level,
        ]);
    }

    public function logForUser($message, $context = [])
    {
        if (is_string($context)) {
            $context = ['context' => $context];
        };

        $this->logger->log($this->level, $message, $context);
    }
}
