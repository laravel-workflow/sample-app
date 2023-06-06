<?php

declare(strict_types=1);

namespace App\Workflows\Transfer;

use Throwable;
use Workflow\ActivityStub;
use Workflow\Workflow;

class TransferWorkflow extends Workflow
{
    public function execute($fromAccountId, $toAccountId, $amount)
    {
        try {
            yield ActivityStub::make(WithdrawActivity::class, $fromAccountId, $amount);
            $this->addCompensation(fn () => ActivityStub::make(DepositActivity::class, $fromAccountId, $amount));

            yield ActivityStub::make(DepositActivity::class, $toAccountId, $amount);
            $this->addCompensation(fn () => ActivityStub::make(WithdrawActivity::class, $toAccountId, $amount));
        } catch (Throwable $th) {
            yield from $this->compensate();
            throw $th;
        }
    }
}
