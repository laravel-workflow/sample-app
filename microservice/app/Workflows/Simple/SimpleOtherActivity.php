<?php

declare(strict_types=1);

namespace App\Workflows\Simple;

use Workflow\Activity;

class SimpleOtherActivity extends Activity
{
    public function execute($string)
    {
        return $string;
    }
}
