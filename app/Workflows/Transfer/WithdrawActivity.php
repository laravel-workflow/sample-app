<?php

declare(strict_types=1);

namespace App\Workflows\Transfer;

use App\Domain\Accounts\Commands\WithdrawCurrency;
use Spatie\EventSourcing\Commands\CommandBus;
use Workflow\Activity;

class WithdrawActivity extends Activity
{
    public $tries = 3;

    public function execute($accountId, $amount)
    {
        app(CommandBus::class)->dispatch(new WithdrawCurrency($accountId, $amount));
    }
}
