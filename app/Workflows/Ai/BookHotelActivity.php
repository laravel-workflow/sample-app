<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookHotelActivity extends Activity
{
    public function execute()
    {
        $id = random_int(100000, 999999);
        error_log('Booking hotel... id ' . $id);
        return $id;
    }
}
