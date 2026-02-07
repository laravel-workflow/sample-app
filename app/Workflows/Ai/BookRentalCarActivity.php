<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookRentalCarActivity extends Activity
{
    public function execute(string $pickupLocation, string $pickupDate, string $returnDate)
    {
        $id = random_int(100000, 999999);
        error_log('Booking rental car at ' . $pickupLocation . ' from ' . $pickupDate . ' to ' . $returnDate . '. Confirmation #' . $id);

        return 'Rental car booked at ' . $pickupLocation . ' from ' . $pickupDate . ' to ' . $returnDate . '. Confirmation #' . $id;
    }
}
