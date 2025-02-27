<?php

namespace App\Console\Commands;

use App\Workflows\Microservice\MicroserviceWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Microservice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:microservice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a microservice workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workflow = WorkflowStub::make(MicroserviceWorkflow::class);
        $workflow->start();
        while ($workflow->running());
        $this->info($workflow->output());
    }
}
