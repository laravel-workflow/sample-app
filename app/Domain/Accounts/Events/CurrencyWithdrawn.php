<?php

namespace App\Domain\Accounts\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CurrencyWithdrawn extends ShouldBeStored
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
