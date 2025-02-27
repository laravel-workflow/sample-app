<?php

namespace App\Models;

use Workflow\Models\StoredWorkflowLog as BaseStoredWorkflowLog;

class StoredWorkflowLog extends BaseStoredWorkflowLog
{
    protected $connection = 'shared';
}
