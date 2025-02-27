<?php

namespace App\Models;

use Workflow\Models\StoredWorkflow as BaseStoredWorkflow;

class StoredWorkflow extends BaseStoredWorkflow
{
    protected $connection = 'shared';
}
