<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Workflow\WorkflowStub;

class BookFlight implements Tool
{
    public function __construct(
        private readonly int $workflowId,
    ) {}

    public function description(): Stringable|string
    {
        return 'Book a flight for the user.';
    }

    public function handle(Request $request): Stringable|string
    {
        $workflow = WorkflowStub::load($this->workflowId);
        $workflow->send(json_encode([
            'type' => 'book_flight',
            'origin' => $request['origin'],
            'destination' => $request['destination'],
            'departure_date' => $request['departure_date'],
        ]));

        return 'Booking flight from ' . $request['origin'] . ' to ' . $request['destination'] . ' on ' . $request['departure_date'];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'origin' => $schema->string()->required()->description('Departure airport or city'),
            'destination' => $schema->string()->required()->description('Arrival airport or city'),
            'departure_date' => $schema->string()->required()->description('Departure date (YYYY-MM-DD)'),
        ];
    }
}
