<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class CancelFlightActivity extends Activity
{
    public function execute($flightId)
    {
        error_log('Cancelling flight ' . $flightId . '...');
    }
}
