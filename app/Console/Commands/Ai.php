<?php

namespace App\Console\Commands;

use App\Workflows\Ai\AiWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Ai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs an AI workflow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $workflow = WorkflowStub::make(AiWorkflow::class);
        $workflow->start();
        $workflow->send('Hello, AI! What can you do?');

        $message = null;
        do {
            $message = $workflow->receive();
            sleep(2);
        } while ($message === null);

        $this->info($message);
        $lastMessage = $message;

        $workflow->send('Book a hotel');

        $message = null;
        do {
            $message = $workflow->receive();
            sleep(2);
        } while ($message === null || $message === $lastMessage);

        $this->info($message);
    }
}
