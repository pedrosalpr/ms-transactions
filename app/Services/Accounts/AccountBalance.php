<?php

namespace App\Services\Accounts;

class AccountBalance extends Account
{

    public function balance(): float
    {
        return $this->account->getBalance();
    }
}
