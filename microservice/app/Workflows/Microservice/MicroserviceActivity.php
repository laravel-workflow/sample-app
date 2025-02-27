<?php

declare(strict_types=1);

namespace App\Workflows\Microservice;

use Workflow\Activity;

class MicroserviceActivity extends Activity
{
    public $connection = 'shared';
    public $queue = 'activity';

    public function execute()
    {
        return 'activity';
    }
}
