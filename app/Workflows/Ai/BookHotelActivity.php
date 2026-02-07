<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookHotelActivity extends Activity
{
    public function execute(string $hotelName, string $checkIn, string $checkOut, int $guests)
    {
        $id = random_int(100000, 999999);
        error_log("Booking hotel: {$hotelName}, {$checkIn} to {$checkOut}, {$guests} guest(s). Confirmation #{$id}");

        return "Hotel booked: {$hotelName}, check-in {$checkIn}, check-out {$checkOut}, {$guests} guest(s). Confirmation #{$id}";
    }
}
