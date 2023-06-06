<?php

namespace App\Domain\Accounts\Projectors;

use App\Domain\Accounts\Events\AccountCreated;
use App\Domain\Accounts\Events\CurrencyDeposited;
use App\Domain\Accounts\Events\CurrencyWithdrawn;
use App\Interfaces\AccountRepositoryInterface;
use App\Interfaces\UuidGeneratorInterface;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class AccountProjector extends Projector
{
    /**
     * @var AccountRepositoryInterface
     * */
    public $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function onAccountCreated(AccountCreated $event)
    {
        $this->accountRepository->createAccount([
            'id' => $event->aggregateRootUuid(),
            'user_id' => $event->userId,
        ]);
    }

    public function onCurrencyDeposited(CurrencyDeposited $event)
    {
        $this->accountRepository->increaseBalance($event->aggregateRootUuid(), $event->amount);
    }

    public function onCurrencyWithdrawn(CurrencyWithdrawn $event)
    {
        $this->accountRepository->decreaseBalance($event->aggregateRootUuid(), $event->amount);
    }
}
