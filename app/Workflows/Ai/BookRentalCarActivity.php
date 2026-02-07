<?php

namespace App\Workflows\Ai;

use Workflow\Activity;

class BookRentalCarActivity extends Activity
{
    public function execute()
    {
        $id = random_int(100000, 999999);
        error_log('Booking rental car... id ' . $id);
        return $id;
    }
}
