<?php

namespace App\Domain\Accounts\Reactors;

use App\Domain\Accounts\Events\AccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class AccountCreatedReactor extends Reactor implements ShouldQueue
{
    public function onAccountCreated(AccountCreated $event)
    {
    }
}
