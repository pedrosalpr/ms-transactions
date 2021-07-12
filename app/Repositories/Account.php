<?php

namespace App\Repositories;

use App\Entities\Users\User;
use App\Models\Transaction;

class Account
{
    /**
     * Entity User
     *
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Sums all user transactions
     *
     * @return float
     */
    public function getBalance(): float
    {
        $value = Transaction::where('user_id', $this->user->getId())->sum('value');
        return number_format($value, 2);
    }
}
