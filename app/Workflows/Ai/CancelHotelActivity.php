<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class CancelHotelActivity extends Activity
{
    public function execute($hotelId)
    {
        error_log('Cancelling hotel ' . $hotelId . '...');
    }
}
