<?php

declare(strict_types=1);

namespace App\Workflows\Simple;

use Workflow\SignalMethod;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class SimpleWorkflow extends Workflow
{
    private bool $paymentReceived = false;

    #[SignalMethod]
    public function receivePayment()
    {
        $this->paymentReceived = true;
    }

    public function execute()
    {
        yield WorkflowStub::await(fn () => $this->paymentReceived);

        return 'workflow';
    }
}
