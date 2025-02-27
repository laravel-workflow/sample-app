<?php

declare(strict_types=1);

namespace App\Workflows\Microservice;

use Workflow\ActivityStub;
use Workflow\Workflow;

class MicroserviceWorkflow extends Workflow
{
    public $connection = 'shared';
}
