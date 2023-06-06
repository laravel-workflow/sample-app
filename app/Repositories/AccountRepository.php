<?php

namespace App\Repositories;

use App\Interfaces\AccountRepositoryInterface;
use App\Models\Account;

class AccountRepository implements AccountRepositoryInterface
{
    public function createAccount(array $attributes)
    {
        return Account::create($attributes);
    }

    public function increaseBalance($id, $amount)
    {
        $account = Account::findOrFail($id);
        $account->balance += $amount;
        return $account->save();
    }

    public function decreaseBalance($id, $amount)
    {
        $account = Account::findOrFail($id);
        $account->balance -= $amount;
        return $account->save();
    }
}
