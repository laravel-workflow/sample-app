<?php

namespace App\Console\Commands;

use App\Workflows\Ai\AiWorkflow;
use Illuminate\Console\Command;
use Workflow\WorkflowStub;

class Ai extends Command
{
    protected $signature = 'app:ai {--inject-failure= : Inject failure into a booking activity (hotel, flight, car)}';

    protected $description = 'Interactive AI travel agent powered by a durable workflow';

    public function handle()
    {
        $injectFailure = $this->option('inject-failure');

        $workflow = WorkflowStub::make(AiWorkflow::class);
        $workflow->start($injectFailure);

        $this->info('Travel Agent started. Type your messages below. Type "quit" to exit.');
        $this->newLine();

        while (true) {
            $input = $this->ask('You');

            if ($input === null || strtolower(trim($input)) === 'quit') {
                $this->info('Goodbye!');
                break;
            }

            if (trim($input) === '') {
                continue;
            }

            $workflow->send($input);
            if (! $this->waitForMessage($workflow)) {
                break;
            }
        }

        return 0;
    }

    /**
     * Poll the workflow outbox until one message arrives, then display it.
     */
    private function waitForMessage($workflow, int $timeout = 120): bool
    {
        $elapsed = 0;

        while ($elapsed < $timeout) {
            $message = $workflow->receive();

            if ($message !== null) {
                $this->newLine();
                $this->line("<comment>Agent:</comment> {$message}");
                return ! $workflow->fresh()->failed() && ! $workflow->fresh()->completed();
            }

            if ($workflow->fresh()->failed() || $workflow->fresh()->completed()) {
                return false;
            }

            sleep(2);
            $elapsed += 2;
        }

        $this->error('Timed out waiting for a response.');
        return false;
    }
}
