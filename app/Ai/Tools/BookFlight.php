<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class BookFlight implements Tool
{
    public static array $pending = [];

    public function description(): Stringable|string
    {
        return 'Book a flight for the user.';
    }

    public function handle(Request $request): Stringable|string
    {
        self::$pending[] = [
            'type' => 'book_flight',
            'origin' => $request['origin'],
            'destination' => $request['destination'],
            'departure_date' => $request['departure_date'],
            'return_date' => $request['return_date'] ?? null,
        ];

        $summary = 'Booking flight from ' . $request['origin'] . ' to ' . $request['destination'] . ' departing ' . $request['departure_date'];
        if (! empty($request['return_date'])) {
            $summary .= ', returning ' . $request['return_date'];
        }

        return $summary;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'origin' => $schema->string()->required()->description('Departure airport or city'),
            'destination' => $schema->string()->required()->description('Arrival airport or city'),
            'departure_date' => $schema->string()->required()->description('Departure date (YYYY-MM-DD)'),
            'return_date' => $schema->string()->description('Return date (YYYY-MM-DD). Omit for one-way flights.'),
        ];
    }
}
