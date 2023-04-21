<?php

declare(strict_types=1);

namespace App\Workflows\Simple;

use Workflow\Activity;

class SimpleActivity extends Activity
{
    public function execute()
    {
        return 'activity';
    }
}
