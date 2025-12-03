<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\StoredWorkflow;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Workflow\States\WorkflowCompletedStatus;
use Workflow\States\WorkflowFailedStatus;
use Workflow\WorkflowStub;

class GetWorkflowResultTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Fetch the status and, if completed, the output of a Laravel Workflow.
        
        Use the workflow_id returned by `start_workflow` to check on a
        workflow's progress. Once the status is `WorkflowCompletedStatus`,
        the output field will contain the workflow's result.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'workflow_id' => ['required'],
        ]);

        $workflowId = $data['workflow_id'];

        // Verify the workflow exists in the database
        $stored = StoredWorkflow::find($workflowId);

        if (! $stored) {
            return Response::json([
                'found' => false,
                'workflow_id' => $workflowId,
                'message' => "Workflow {$workflowId} not found.",
            ]);
        }

        try {
            $workflow = WorkflowStub::load($workflowId);

            $status = $workflow->status();
            $statusName = is_object($status) ? class_basename($status) : (string) $status;
            $running = $workflow->running();

            $result = null;
            $error = null;

            // Get output if workflow is completed
            if (! $running && str_contains($statusName, 'Completed')) {
                $result = $workflow->output();
            }

            // Get error details if workflow failed
            if (! $running && str_contains($statusName, 'Failed')) {
                $exception = $stored->exceptions()->latest()->first();
                $error = $exception?->exception ?? 'Unknown error';
            }

            return Response::json([
                'found' => true,
                'workflow_id' => $workflowId,
                'workflow_class' => $stored->class,
                'status' => $statusName,
                'running' => $running,
                'output' => $result,
                'error' => $error,
                'created_at' => $stored->created_at?->toIso8601String(),
                'updated_at' => $stored->updated_at?->toIso8601String(),
            ]);
        } catch (\Throwable $e) {
            return Response::error("Failed to load workflow: {$e->getMessage()}");
        }
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'workflow_id' => $schema->string()
                ->description('The workflow ID returned by start_workflow.'),
        ];
    }
}
