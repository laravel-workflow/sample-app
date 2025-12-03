<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\StoredWorkflow;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class ListWorkflowsTool extends Tool
{
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        List available workflow types and optionally show recent workflow runs.
        
        Use this tool to discover what workflows can be started, or to see
        the status of recent workflow executions.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $data = $request->validate([
            'show_recent' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'status' => ['nullable', 'string'],
        ]);

        $showRecent = $data['show_recent'] ?? false;
        $limit = $data['limit'] ?? 10;
        $statusFilter = $data['status'] ?? null;

        // Get available workflows from config
        $availableWorkflows = [];
        foreach (config('workflow_mcp.workflows', []) as $key => $class) {
            $availableWorkflows[] = [
                'key' => $key,
                'class' => $class,
            ];
        }

        $response = [
            'available_workflows' => $availableWorkflows,
            'allow_fqcn' => config('workflow_mcp.allow_fqcn', false),
        ];

        // Optionally include recent workflow runs
        if ($showRecent) {
            $query = StoredWorkflow::query()
                ->orderBy('created_at', 'desc')
                ->limit($limit);

            if ($statusFilter) {
                $query->where('status', 'like', "%{$statusFilter}%");
            }

            $recentWorkflows = $query->get()->map(function ($workflow) {
                $status = $workflow->status;
                $statusName = is_object($status) ? class_basename($status) : class_basename((string) $status);

                return [
                    'id' => $workflow->id,
                    'class' => $workflow->class,
                    'status' => $statusName,
                    'created_at' => $workflow->created_at?->toIso8601String(),
                    'updated_at' => $workflow->updated_at?->toIso8601String(),
                ];
            });

            $response['recent_workflows'] = $recentWorkflows;
        }

        return Response::json($response);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'show_recent' => $schema->boolean()
                ->description('Whether to include recent workflow runs in the response.'),

            'limit' => $schema->integer()
                ->description('Maximum number of recent workflows to return (default: 10, max: 50).'),

            'status' => $schema->string()
                ->description('Filter recent workflows by status (e.g., "Completed", "Failed", "Running").'),
        ];
    }
}
