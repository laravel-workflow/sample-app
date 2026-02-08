<?php

namespace App\Workflows\Ai;

use Illuminate\Support\Facades\Log;
use Workflow\Activity;
use Workflow\Exceptions\NonRetryableException;

class BookRentalCarActivity extends Activity
{
    public function execute(string $pickupLocation, string $pickupDate, string $returnDate, bool $shouldFail = false)
    {
        if ($shouldFail) {
            throw new NonRetryableException("Rental car booking failed: {$pickupLocation}.");
        }

        $id = random_int(100000, 999999);
        Log::error('Booking rental car at ' . $pickupLocation . ' from ' . $pickupDate . ' to ' . $returnDate . '. Confirmation #' . $id);

        return 'Rental car booked at ' . $pickupLocation . ' from ' . $pickupDate . ' to ' . $returnDate . '. Confirmation #' . $id;
    }
}
