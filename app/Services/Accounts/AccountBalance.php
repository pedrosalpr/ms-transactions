<?php

namespace App\Services\Accounts;

class AccountBalance extends Account
{

    /**
     * Returns user account balance
     *
     * @return float
     */
    public function balance(): float
    {
        return $this->account->getBalance();
    }
}
