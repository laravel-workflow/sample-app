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
    protected $signature = 'app:ai {--inject-failure= : Inject failure into a booking activity (hotel, flight, car)}';

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
        $injectFailure = $this->option('inject-failure');

        $workflow = WorkflowStub::make(AiWorkflow::class);
        $workflow->start($injectFailure);

        $workflow->send('Hello, AI! What can you do?');

        $message = $this->receiveMessage($workflow);
        if ($message === null) {
            $this->error('Timed out waiting for AI response.');
            return 1;
        }
        $this->info($message);

        $workflow->send(
            'Book the Grand Hotel in Paris for 2 guests, checking in 2026-03-15 and checking out 2026-03-20. '
            . 'Also book a flight from New York to Paris departing 2026-03-15. '
            . 'Also book a rental car in Paris from 2026-03-15 to 2026-03-20.'
        );

        // Expect: AI response + 3 booking confirmations
        for ($i = 0; $i < 4; $i++) {
            $message = $this->receiveMessage($workflow);
            if ($message === null) {
                $this->error('Workflow did not complete — a booking may have failed.');
                return 1;
            }
            $this->info($message);
        }

        $this->info('All bookings completed. Workflow finished!');
        return 0;
    }

    /**
     * Poll the workflow for the next outbox message.
     */
    private function receiveMessage($workflow, int $timeout = 120): ?string
    {
        $elapsed = 0;
        while ($elapsed < $timeout) {
            $message = $workflow->receive();
            if ($message !== null) {
                return $message;
            }
            sleep(2);
            $elapsed += 2;
        }
        return null;
    }
}
