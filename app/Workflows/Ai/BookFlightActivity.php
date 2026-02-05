<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookFlightActivity extends Activity
{
    public function execute()
    {
        error_log('Booking flight...');
    }
}
