<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Arr;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class StartWorkflowTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Start a Laravel Workflow asynchronously and return its workflow ID.
        
        The workflow will execute in the background on the queue. Use the
        `get_workflow_result` tool to poll for status and retrieve results
        once the workflow completes.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'workflow' => ['required', 'string'],
            'args' => ['nullable', 'array'],
            'external_id' => ['nullable', 'string', 'max:255'],
        ]);

        $workflowKey = $data['workflow'];
        $args = Arr::get($data, 'args', []);
        $externalId = $data['external_id'] ?? null;

        // Resolve workflow class from config mapping or FQCN
        $workflowClass = $this->resolveWorkflowClass($workflowKey);

        if ($workflowClass === null) {
            return Response::error("Unknown workflow: {$workflowKey}. Check available workflows in config/workflow_mcp.php.");
        }

        // Validate the class exists and is a Workflow subclass
        if (! class_exists($workflowClass)) {
            return Response::error("Workflow class not found: {$workflowClass}");
        }

        if (! is_subclass_of($workflowClass, Workflow::class)) {
            return Response::error("Class {$workflowClass} is not a valid Workflow.");
        }

        try {
            $stub = WorkflowStub::make($workflowClass);

            // Start the workflow asynchronously with the provided arguments
            $stub->start(...array_values($args));

            $workflowId = $stub->id();
            $status = $stub->status();
            $statusName = is_object($status) ? class_basename($status) : class_basename((string) $status);

            return Response::json([
                'workflow_id' => $workflowId,
                'workflow' => $workflowKey,
                'workflow_class' => $workflowClass,
                'status' => $statusName,
                'external_id' => $externalId,
                'message' => 'Workflow started successfully. Use get_workflow_result to poll status and output.',
            ]);
        } catch (\Throwable $e) {
            return Response::error("Failed to start workflow: {$e->getMessage()}");
        }
    }

    /**
     * Resolve a workflow class from a key or FQCN.
     */
    protected function resolveWorkflowClass(string $key): ?string
    {
        // First check the config mapping
        $mapped = config("workflow_mcp.workflows.{$key}");
        if ($mapped !== null) {
            return $mapped;
        }

        // If FQCN is allowed, check if the key looks like a class name
        if (config('workflow_mcp.allow_fqcn', false) && str_contains($key, '\\')) {
            return $key;
        }

        return null;
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        $availableWorkflows = array_keys(config('workflow_mcp.workflows', []));
        $workflowList = implode(', ', $availableWorkflows);

        return [
            'workflow' => $schema->string()
                ->description("The workflow key or class to start. Available workflows: {$workflowList}"),

            'args' => $schema->object()
                ->description('Arguments for the workflow execute() method, as a JSON object. The values will be passed in order to the execute method.'),

            'external_id' => $schema->string()
                ->description('Optional idempotency/correlation key provided by the caller for tracking purposes.'),
        ];
    }
}
