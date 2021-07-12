<?php

namespace App\Services\Accounts;

use App\Entities\Users\User;
use App\Repositories\Account as RepositoriesAccount;

abstract class Account
{
    /**
     * Entity User
     *
     * @var User
     */
    protected $user;

    /**
     * Repository Account
     *
     * @var RepositoriesAccount
     */
    protected $account;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->account = new RepositoriesAccount($this->user);
    }
}
