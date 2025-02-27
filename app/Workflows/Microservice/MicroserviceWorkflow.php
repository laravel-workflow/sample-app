<?php

declare(strict_types=1);

namespace App\Workflows\Microservice;

use Workflow\ActivityStub;
use Workflow\Workflow;

class MicroserviceWorkflow extends Workflow
{
    public function execute()
    {
        $result = yield ActivityStub::make(MicroserviceActivity::class);

        $otherResult = yield ActivityStub::make(MicroserviceOtherActivity::class, 'other');

        return 'workflow_' . $result . '_' . $otherResult;
    }
}
