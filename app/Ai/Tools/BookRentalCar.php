<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use Workflow\WorkflowStub;

class BookRentalCar implements Tool
{
    public function __construct(
        private readonly int $workflowId,
    ) {}

    public function description(): Stringable|string
    {
        return 'Book a rental car for the user.';
    }

    public function handle(Request $request): Stringable|string
    {
        $workflow = WorkflowStub::load($this->workflowId);
        $workflow->send(json_encode([
            'type' => 'book_rental_car',
            'pickup_location' => $request['pickup_location'],
            'pickup_date' => $request['pickup_date'],
            'return_date' => $request['return_date'],
        ]));

        return 'Booking rental car at ' . $request['pickup_location'] . ' from ' . $request['pickup_date'] . ' to ' . $request['return_date'];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'pickup_location' => $schema->string()->required()->description('Pickup location (city or airport)'),
            'pickup_date' => $schema->string()->required()->description('Pickup date (YYYY-MM-DD)'),
            'return_date' => $schema->string()->required()->description('Return date (YYYY-MM-DD)'),
        ];
    }
}
