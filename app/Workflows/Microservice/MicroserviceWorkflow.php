<?php

declare(strict_types=1);

namespace App\Workflows\Microservice;

use Workflow\Workflow;
use function Workflow\activity;

class MicroserviceWorkflow extends Workflow
{
    public function execute()
    {
        $result = yield activity(MicroserviceActivity::class);

        $otherResult = yield activity(MicroserviceOtherActivity::class, 'other');

        return 'workflow_' . $result . '_' . $otherResult;
    }
}
