<?php

declare(strict_types=1);

namespace App\Workflows\Elapsed;

use Workflow\ActivityStub;
use Workflow\Workflow;
use Workflow\WorkflowStub;

/**
 * See: https://laravel-workflow.com/docs/features/timers
 */

class ElapsedTimeWorkflow extends Workflow
{
    public function execute()
    {
        $start = yield WorkflowStub::sideEffect(fn () => WorkflowStub::now());
        
        yield ActivityStub::make(SleepActivity::class, 3);

        $end = yield WorkflowStub::sideEffect(fn () => WorkflowStub::now());

        return 'Elapsed Time: ' . $start->diffInSeconds($end) . ' seconds';
    }
}
