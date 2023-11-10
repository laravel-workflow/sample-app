<?php

namespace Tests\Feature\Workflows\Simple;

use App\Workflows\Simple\SimpleActivity;
use App\Workflows\Simple\SimpleOtherActivity;
use App\Workflows\Simple\SimpleWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Workflow\WorkflowStub;

class SimpleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function testWorkflow(): void
    {
        WorkflowStub::fake();

        WorkflowStub::mock(SimpleActivity::class, 'activity');

        WorkflowStub::mock(SimpleOtherActivity::class, function ($context, $string) {
            $this->assertSame($string, 'other');
            return $string;
        });

        $workflow = WorkflowStub::make(SimpleWorkflow::class);
        $workflow->start();

        $this->assertSame($workflow->output(), 'workflow_activity_other');
    }
}
