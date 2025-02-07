<?php

namespace App\Workflows;

use Workflow\ActivityStub;
use Workflow\Workflow;

class CheckConsoleErrorsWorkflow extends Workflow
{
    public function execute(string $url)
    {
        $errors = yield ActivityStub::make(CheckConsoleErrorsActivity::class, $url);

        return $errors;
    }
}
