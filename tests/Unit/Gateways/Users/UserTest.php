<?php

namespace Tests\Unit\Gateways\Users;

use App\Enums\Users\UserType;
use App\Exceptions\Gateways\ClientApiException;
use App\Services\Gateways\Users\UserClientApi;
use Database\Factories\UserFactory;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testShouldReturnMicroserviceUser()
    {
        $userId = 10;
        $userFactory = (new UserFactory)->make(['id' => $userId, 'userType' => UserType::COMMON]);
        $userMock = $this->createMock(UserClientApi::class);
        $userMock->method('findUser')->willReturn($userFactory);
        $this->assertEquals($userFactory, $userMock->findUser($userId));
    }
}
