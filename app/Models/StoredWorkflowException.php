<?php

namespace App\Models;

use Workflow\Models\StoredWorkflowException as BaseStoredWorkflowException;

class StoredWorkflowException extends BaseStoredWorkflowException
{
    protected $connection = 'shared';
}
