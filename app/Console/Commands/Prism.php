<?php

namespace App\Console\Commands;

use App\Workflows\Prism\PrismWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Prism extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prism';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a Prism AI workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workflow = WorkflowStub::make(PrismWorkflow::class);
        $workflow->start();
        while ($workflow->running());
        $user = $workflow->output();

        $this->info('Generated User:');
        $this->info(json_encode($user, JSON_PRETTY_PRINT));
    }
}
