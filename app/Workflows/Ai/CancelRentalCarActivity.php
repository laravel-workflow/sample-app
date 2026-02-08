<?php

namespace App\Workflows\Ai;

use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class CancelRentalCarActivity extends Activity
{
    public function execute($rentalCarId)
    {
        Log::error('Cancelling rental car ' . $rentalCarId . '...');
    }
}
