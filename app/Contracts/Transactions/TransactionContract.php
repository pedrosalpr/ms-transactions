<?php

namespace App\Contracts\Transactions;

use App\Entities\Users\User;

interface TransactionContract
{
    public function transact(float $value);

    public function getMessage(): string;

    public function setUserPayer(User $userPayer): void;

    public function setUserPayee(User $userPayee): void;
}
