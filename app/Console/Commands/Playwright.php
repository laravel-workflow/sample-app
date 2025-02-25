<?php

namespace App\Console\Commands;

use App\Workflows\Playwright\CheckConsoleErrorsWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Playwright extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:playwright';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a playwright workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * See: https://laravel-workflow.com/blog/automating-qa-with-playwright-and-laravel-workflow
         */

        $workflow = WorkflowStub::make(CheckConsoleErrorsWorkflow::class);
        $workflow->start('https://example.com');
        while ($workflow->running());
        $this->info($workflow->output()['mp4']);
    }
}
