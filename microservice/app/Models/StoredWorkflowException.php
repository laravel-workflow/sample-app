<?php

namespace App\Models;

use Workflow\Models\StorStoredWorkflowExceptionedWorkflow as BaseStoredWorkflowException;

class StoredWorkflowException extends BaseStoredWorkflowException
{
    protected $connection = 'shared';
}
