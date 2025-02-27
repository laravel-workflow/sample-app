<?php

namespace App\Models;

use Workflow\Models\StoredWorkflowTimer as BaseStoredWorkflowTimer;

class StoredWorkflowTimer extends BaseStoredWorkflowTimer
{
    protected $connection = 'shared';
}
