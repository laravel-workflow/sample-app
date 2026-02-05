<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookRentalCarActivity extends Activity
{
    public function execute()
    {
        error_log('Booking rental car...');
    }
}
