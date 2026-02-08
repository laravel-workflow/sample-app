<?php

namespace App\Workflows\Ai;

use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class CancelHotelActivity extends Activity
{
    public function execute($hotelId)
    {
        Log::error('Cancelling hotel ' . $hotelId . '...');
    }
}
