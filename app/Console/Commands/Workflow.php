<?php

namespace App\Console\Commands;

use App\Workflows\Simple\SimpleWorkflow;
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
        $workflow = WorkflowStub::make(SimpleWorkflow::class);
        $workflow->start();
        while ($workflow->running());
        $this->info($workflow->output());
    }
}
