<?php

namespace Lightworx\FilamentSettings\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'filament-settings:install';
    protected $description = 'Install the Filament Settings package';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->comment('Publishing Package Configuration...');
        $this->callSilent('migrate');
        $this->info('Filament Settings was installed successfully.');
        return 0;
    }
}
