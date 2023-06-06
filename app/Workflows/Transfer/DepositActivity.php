<?php

declare(strict_types=1);

namespace App\Workflows\Transfer;

use App\Domain\Accounts\Commands\DepositCurrency;
use Spatie\EventSourcing\Commands\CommandBus;
use Workflow\Activity;

class DepositActivity extends Activity
{
    public $tries = 3;

    public function execute($accountId, $amount)
    {
        app(CommandBus::class)->dispatch(new DepositCurrency($accountId, $amount));
    }
}
