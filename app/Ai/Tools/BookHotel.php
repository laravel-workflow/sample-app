<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Workflow\WorkflowStub;

class BookHotel implements Tool
{
    public function __construct(
        private readonly int $workflowId,
    ) {}

    public function description(): Stringable|string
    {
        return 'Book a hotel for the user.';
    }

    public function handle(Request $request): Stringable|string
    {
        $workflow = WorkflowStub::load($this->workflowId);
        $workflow->send(json_encode([
            'type' => 'book_hotel',
            'hotel_name' => $request['hotel_name'],
            'check_in_date' => $request['check_in_date'],
            'check_out_date' => $request['check_out_date'],
            'guests' => (int) $request['guests'],
        ]));

        return 'Booking hotel: ' . $request['hotel_name'] . ' from ' . $request['check_in_date'] . ' to ' . $request['check_out_date'] . ' for ' . $request['guests'] . ' guest(s)';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'hotel_name' => $schema->string()->required()->description('The name and location of the hotel to book'),
            'check_in_date' => $schema->string()->required()->description('Check-in date (YYYY-MM-DD)'),
            'check_out_date' => $schema->string()->required()->description('Check-out date (YYYY-MM-DD)'),
            'guests' => $schema->integer()->required()->description('Number of guests'),
        ];
    }
}
