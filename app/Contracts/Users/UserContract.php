<?php

namespace App\Contracts\Users;

use App\Entities\Users\User;

interface UserContract
{
    public function getEntity(int $id): User;
}
