<?php

namespace App\Workflows\Prism;

use Workflow\ActivityStub;
use Workflow\Workflow;

class PrismWorkflow extends Workflow
{
    public function execute()
    {
        do {
            $user = yield ActivityStub::make(GenerateUserActivity::class);
            $valid = yield ActivityStub::make(ValidateUserActivity::class, $user);
        } while (!$valid);

        return $user;
    }
}
