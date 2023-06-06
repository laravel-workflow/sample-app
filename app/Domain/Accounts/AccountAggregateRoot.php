<?php

namespace App\Domain\Accounts;

use App\Domain\Accounts\Commands\CreateAccount;
use App\Domain\Accounts\Commands\DepositCurrency;
use App\Domain\Accounts\Commands\WithdrawCurrency;
use App\Domain\Accounts\Events\AccountCreated;
use App\Domain\Accounts\Events\CurrencyDeposited;
use App\Domain\Accounts\Events\CurrencyWithdrawn;
use Exception;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class AccountAggregateRoot extends AggregateRoot
{
    protected bool $created = false;
    protected string $userId;
    protected float $balance = 0;

    public function applyAccountCreated(AccountCreated $event)
    {
        $this->created = true;
        $this->userId = $event->userId;
        $this->balance = 0;
    }

    public function applyCurrencyDeposited(CurrencyDeposited $event)
    {
        $this->balance += $event->amount;
    }

    public function applyCurrencyWithdrawn(CurrencyWithdrawn $event)
    {
        $this->balance -= $event->amount;
    }

    public function createAccount(CreateAccount $command)
    {
        if ($this->created) {
            throw new Exception('Account already exsits.');
        }

        $this->recordThat(new AccountCreated($command->userId));

        return $this;
    }

    public function depositCurrency(DepositCurrency $command)
    {
        $this->recordThat(new CurrencyDeposited($command->amount));

        return $this;
    }

    public function withdrawCurrency(WithdrawCurrency $command)
    {
        if ($this->balance < $command->amount) {
            throw new Exception('Insufficient balance.');
        }

        $this->recordThat(new CurrencyWithdrawn($command->amount));

        return $this;
    }
}
