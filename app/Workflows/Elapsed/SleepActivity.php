<?php

declare(strict_types=1);

namespace App\Workflows\Elapsed;

use Workflow\Activity;

class SleepActivity extends Activity
{
    public function execute($seconds)
    {
        sleep($seconds);
    }
}
