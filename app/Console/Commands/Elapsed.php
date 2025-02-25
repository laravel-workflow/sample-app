<?php

namespace App\Console\Commands;

use App\Workflows\Elapsed\ElapsedTimeWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Elapsed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:elapsed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an elapsed time workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * See: https://laravel-workflow.com/docs/features/timers
         */

        $workflow = WorkflowStub::make(ElapsedTimeWorkflow::class);
        $workflow->start();
        while ($workflow->running());
        $this->info($workflow->output());
    }
}
