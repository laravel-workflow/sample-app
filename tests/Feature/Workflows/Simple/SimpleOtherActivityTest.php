<?php

namespace Tests\Feature\Workflows\Simple;

use App\Workflows\Simple\SimpleOtherActivity;
use App\Workflows\Simple\SimpleWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Workflow\Models\StoredWorkflow;
use Workflow\WorkflowStub;

class SimpleOtherActivityTest extends TestCase
{
    use RefreshDatabase;

    public function testActivity(): void
    {
        $workflow = WorkflowStub::make(SimpleWorkflow::class);

        $activity = new SimpleOtherActivity(0, now()->toDateTimeString(), StoredWorkflow::findOrFail($workflow->id()), 'other');

        $result = $activity->handle();

        $this->assertSame($result, 'other');
    }
}
