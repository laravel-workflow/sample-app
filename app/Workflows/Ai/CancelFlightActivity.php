<?php

namespace App\Workflows\Ai;

use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class CancelFlightActivity extends Activity
{
    public function execute($flightId)
    {
        Log::error('Cancelling flight ' . $flightId . '...');
    }
}
