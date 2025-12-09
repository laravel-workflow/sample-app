<?php

namespace App\Workflows\Prism;

use Workflow\Workflow;
use function Workflow\activity;

class PrismWorkflow extends Workflow
{
    public function execute()
    {
        do {
            $user = yield activity(GenerateUserActivity::class);
            $valid = yield activity(ValidateUserActivity::class, $user);
        } while (!$valid);

        return $user;
    }
}
