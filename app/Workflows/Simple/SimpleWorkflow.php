<?php

declare(strict_types=1);

namespace App\Workflows\Simple;

use Workflow\Workflow;
use function Workflow\activity;

class SimpleWorkflow extends Workflow
{
    public function execute()
    {
        $result = yield activity(SimpleActivity::class);

        $otherResult = yield activity(SimpleOtherActivity::class, 'other');

        return 'workflow_' . $result . '_' . $otherResult;
    }
}
