<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Workflow MCP Mapping
    |--------------------------------------------------------------------------
    |
    | This configuration file defines the mapping between workflow aliases
    | and their fully qualified class names. This provides a safer way to
    | expose workflows via MCP without allowing arbitrary class execution.
    |
    | Set 'allow_fqcn' to true to allow direct FQCN usage (less secure).
    |
    */

    'allow_fqcn' => env('WORKFLOW_MCP_ALLOW_FQCN', false),

    'workflows' => [
        'simple' => App\Workflows\Simple\SimpleWorkflow::class,
        'prism' => App\Workflows\Prism\PrismWorkflow::class,
        // Add more workflow mappings here as needed
    ],
];
