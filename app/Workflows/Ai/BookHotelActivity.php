<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookHotelActivity extends Activity
{
    public $tries = 1;

    public function execute(string $hotelName, string $checkIn, string $checkOut, int $guests, bool $shouldFail = false)
    {
        if ($shouldFail) {
            throw new \RuntimeException("Hotel booking failed: {$hotelName}");
        }

        $id = random_int(100000, 999999);
        error_log("Booking hotel: {$hotelName}, {$checkIn} to {$checkOut}, {$guests} guest(s). Confirmation #{$id}");

        return "Hotel booked: {$hotelName}, check-in {$checkIn}, check-out {$checkOut}, {$guests} guest(s). Confirmation #{$id}";
    }
}
