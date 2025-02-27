<?php

namespace App\Models;

use Workflow\Models\StoredWorkflowSignal as BaseStoredWorkflowSignal;

class StoredWorkflowSignal extends BaseStoredWorkflowSignal
{
    protected $connection = 'shared';
}
