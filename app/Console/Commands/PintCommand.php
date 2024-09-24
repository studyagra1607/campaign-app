<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pint {flags?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Laravel Pint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arguments = implode(' ', $this->argument('flags'));
        passthru('php vendor/bin/pint '.$arguments);
    }
}
