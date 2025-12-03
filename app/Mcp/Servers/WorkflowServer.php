<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\GetWorkflowResultTool;
use App\Mcp\Tools\ListWorkflowsTool;
use App\Mcp\Tools\StartWorkflowTool;
use Laravel\Mcp\Server;

class WorkflowServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Laravel Workflow Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '1.0.0';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        This server allows you to start and monitor Laravel Workflows.

        ## Available Tools

        ### list_workflows
        Discover available workflows and optionally view recent workflow runs.

        ### start_workflow
        Start a Laravel Workflow asynchronously. Returns a workflow_id that you can use to check status.

        ### get_workflow_result
        Check the status of a running workflow and retrieve its output once completed.

        ## Typical Usage Pattern

        1. Call `list_workflows` to see what workflows are available.
        2. Call `start_workflow` with the workflow name and any required arguments.
        3. Store the returned `workflow_id`.
        4. Periodically call `get_workflow_result` with the `workflow_id` to check progress.
        5. When status becomes `WorkflowCompletedStatus`, read the `output` field for results.
        6. If status becomes `WorkflowFailedStatus`, check the `error` field for details.

        ## Status Values

        - `WorkflowCreatedStatus` - Workflow has been created
        - `WorkflowPendingStatus` - Workflow is queued for execution
        - `WorkflowRunningStatus` - Workflow is currently executing
        - `WorkflowWaitingStatus` - Workflow is waiting (e.g., for a timer or signal)
        - `WorkflowCompletedStatus` - Workflow finished successfully (output available)
        - `WorkflowFailedStatus` - Workflow encountered an error (error details available)
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        ListWorkflowsTool::class,
        StartWorkflowTool::class,
        GetWorkflowResultTool::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        //
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}
