<?php

namespace App\Repositories;

use App\Entities\Users\User;
use App\Models\Transaction;

class Account
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getBalance(): float
    {
        $value = Transaction::where('user_id', $this->user->getId())->sum('value');
        return number_format($value, 2);
    }
}
