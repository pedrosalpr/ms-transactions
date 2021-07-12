<?php

namespace App\Services\Users;

use App\Contracts\Users\UserContract;
use App\Entities\Users\User as UserEntity;
use App\Services\Gateways\Users\UserClientApi;

abstract class User implements UserContract
{
    private $userClient;

    public function __construct(UserClientApi $userClient)
    {
        $this->userClient = $userClient;
    }

    public function getEntity(int $id): UserEntity
    {
        return $this->userClient->findUser($id);
    }
}
