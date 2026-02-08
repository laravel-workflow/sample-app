<?php

namespace App\Workflows\Ai;

use Illuminate\Support\Facades\Log;
use Workflow\Activity;
use Workflow\Exceptions\NonRetryableException;

class BookHotelActivity extends Activity
{
    public function execute(string $hotelName, string $checkIn, string $checkOut, int $guests, bool $shouldFail = false)
    {
        if ($shouldFail) {
            throw new NonRetryableException("Hotel booking failed: {$hotelName}.");
        }

        $id = random_int(100000, 999999);
        Log::error("Booking hotel: {$hotelName}, {$checkIn} to {$checkOut}, {$guests} guest(s). Confirmation #{$id}");

        return "Hotel booked: {$hotelName}, check-in {$checkIn}, check-out {$checkOut}, {$guests} guest(s). Confirmation #{$id}";
    }
}
