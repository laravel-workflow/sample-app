<?php

declare(strict_types=1);

namespace App\Workflows\Elapsed;

use Workflow\Workflow;
use function Workflow\{activity, now, sideEffect};

/**
 * See: https://laravel-workflow.com/docs/features/timers
 */

class ElapsedTimeWorkflow extends Workflow
{
    public function execute()
    {
        $start = yield sideEffect(fn () => now());

        yield activity(SleepActivity::class, 3);

        $end = yield sideEffect(fn () => now());

        return 'Elapsed Time: ' . $start->diffInSeconds($end) . ' seconds';
    }
}
