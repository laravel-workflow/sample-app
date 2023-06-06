<?php

namespace App\Interfaces;

interface AccountRepositoryInterface
{
    public function createAccount(array $attributes);
    public function increaseBalance($id, $amount);
    public function decreaseBalance($id, $amount);
}
