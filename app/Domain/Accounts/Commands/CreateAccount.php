<?php

namespace App\Domain\Accounts\Commands;

use App\Domain\Accounts\AccountAggregateRoot;
use Spatie\EventSourcing\Commands\AggregateUuid;
use Spatie\EventSourcing\Commands\HandledBy;

#[HandledBy(AccountAggregateRoot::class)]
class CreateAccount
{
    public function __construct(
        #[AggregateUuid] public string $uuid,
        public string $userId,
    ) {
    }
}

