<?php

namespace App\Services\Accounts;

use App\Repositories\Account as RepositoriesAccount;
use App\Services\Gateways\Users\UserClientApi;

abstract class Account
{
    protected $user;

    protected $account;

    public function __construct(int $id)
    {
        $userClient = resolve(UserClientApi::class);
        $this->user = $userClient->findUser($id);
        $this->account = new RepositoriesAccount($this->user);
    }
}
