<?php

namespace App\Domain\Accounts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AccountCreated extends ShouldBeStored
{
    /**
     * @var string
     * */
    public $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}
