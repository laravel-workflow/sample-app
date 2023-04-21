<?php

namespace App\Console\Commands;

use App\Workflows\Simple\SimpleWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Workflow extends Command
{
    protected $signature = 'workflow';

    protected $description = 'Runs a workflow';

    public function handle()
    {
        $workflow = WorkflowStub::make(SimpleWorkflow::class);
        $workflow->start();
        while ($workflow->running());
        $this->info($workflow->output());
    }
}
