<?php

namespace App\Console\Commands;

use App\Workflows\CheckConsoleErrorsWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Workflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:workflow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workflow = WorkflowStub::make(CheckConsoleErrorsWorkflow::class);
        $workflow->start('https://aol.com');
        while ($workflow->running());
        $this->info(print_r($workflow->output(), true));
    }
}
