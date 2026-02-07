<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookFlightActivity extends Activity
{
    public function execute(string $origin, string $destination, string $departureDate)
    {
        $id = random_int(100000, 999999);
        error_log('Booking flight: ' . $origin . ' -> ' . $destination . ' on ' . $departureDate . '. Confirmation #' . $id);

        return 'Flight booked: ' . $origin . ' to ' . $destination . ' on ' . $departureDate . '. Confirmation #' . $id;
    }
}
