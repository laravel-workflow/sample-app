<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class CancelRentalCarActivity extends Activity
{
    public function execute($rentalCarId)
    {
        error_log('Cancelling rental car ' . $rentalCarId . '...');
    }
}
