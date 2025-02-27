<?php

declare(strict_types=1);

namespace App\Workflows\Simple;

use Workflow\ActivityStub;
use Workflow\Workflow;

class SimpleWorkflow extends Workflow
{
    public function execute()
    {
        $result = yield ActivityStub::make(SimpleActivity::class);

        $otherResult = yield ActivityStub::make(SimpleOtherActivity::class, 'other');

        return 'workflow_' . $result . '_' . $otherResult;
    }
}
