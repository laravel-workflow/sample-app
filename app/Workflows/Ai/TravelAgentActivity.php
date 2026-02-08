<?php

namespace App\Workflows\Ai;

use App\Ai\Agents\TravelAgent;
use App\Ai\Tools\BookFlight;
use App\Ai\Tools\BookHotel;
use App\Ai\Tools\BookRentalCar;
use Workflow\Activity;

class TravelAgentActivity extends Activity
{
    public function execute($messages)
    {
        $history = array_slice($messages, 0, -1);
        $currentUserMessage = end($messages);

        BookHotel::$pending = [];
        BookFlight::$pending = [];
        BookRentalCar::$pending = [];

        $response = (new TravelAgent())
            ->continue($history)
            ->prompt($currentUserMessage->content);

        $bookings = array_merge(
            BookHotel::$pending,
            BookFlight::$pending,
            BookRentalCar::$pending,
        );

        return json_encode([
            'text' => (string) $response,
            'bookings' => $bookings,
        ]);
    }
}
