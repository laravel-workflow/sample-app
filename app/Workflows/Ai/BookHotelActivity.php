<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookHotelActivity extends Activity
{
    public function execute()
    {
        error_log('Booking hotel...');
    }
}
