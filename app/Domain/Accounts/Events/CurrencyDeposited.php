<?php

namespace App\Domain\Accounts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CurrencyDeposited extends ShouldBeStored
{
    /**
     * @var int
     * */
    public $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
