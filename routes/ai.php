<?php

use App\Mcp\Servers\WorkflowServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/workflows', WorkflowServer::class);
