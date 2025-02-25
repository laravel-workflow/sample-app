<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Workflow\Models\StoredWorkflow;
use Workflow\WorkflowStub;

class Webhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a workflow via a webhook and then signals it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * See: https://laravel-workflow.com/docs/features/webhooks
         */

        $workflowCount = StoredWorkflow::count();

        Http::post('http://localhost/api/webhooks/start/webhook-workflow', [
            'message' => 'world',
        ]);

        $id = StoredWorkflow::count();

        if ($workflowCount === $id) {
            $this->error('Workflow did not start');
            return;
        }

        Http::post("http://localhost/api/webhooks/signal/webhook-workflow/{$id}/ready");

        $workflow = WorkflowStub::load($id);
        while ($workflow->running());
        $this->info($workflow->output());
    }
}
