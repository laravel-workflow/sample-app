<?php

declare(strict_types=1);

namespace App\Workflows\Microservice;

use Workflow\Activity;

class MicroserviceActivity extends Activity
{
    public $queue = 'activity';
}
