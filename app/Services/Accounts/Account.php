<?php

namespace App\Services\Accounts;

use App\Entities\Users\User;
use App\Repositories\Account as RepositoriesAccount;
use App\Services\Gateways\Users\UserClientApi;

abstract class Account
{
    protected $user;

    protected $account;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->account = new RepositoriesAccount($this->user);
    }
}
