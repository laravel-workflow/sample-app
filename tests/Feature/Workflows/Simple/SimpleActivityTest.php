<?php

namespace Tests\Feature\Workflows\Simple;

use App\Workflows\Simple\SimpleActivity;
use App\Workflows\Simple\SimpleWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Workflow\Models\StoredWorkflow;
use Workflow\WorkflowStub;

class SimpleActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testActivity(): void
    {
        $workflow = WorkflowStub::make(SimpleWorkflow::class);

        $activity = new SimpleActivity(0, now()->toDateTimeString(), StoredWorkflow::findOrFail($workflow->id()));

        $result = $activity->handle();

        $this->assertSame($result, 'activity');
    }
}
