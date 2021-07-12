<?php

namespace Tests\Unit\Users;

use App\Enums\Users\UserType;
use App\Exceptions\Gateways\ClientApiException;
use App\Services\Gateways\Users\UserClientApi;
use Database\Factories\UserFactory;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testShouldReturnMicroserviceUser()
    {
        $userId = 10;
        $userFactory = (new UserFactory)->make(['id' => $userId, 'userType' => UserType::COMMON]);
        $userMock = $this->createMock(UserClientApi::class);
        $userMock->method('findUser')->willReturn($userFactory);
        $this->assertEquals($userFactory, $userMock->findUser($userId));
    }

    public function testShouldThrowUserNotFoundException()
    {
        $userStub = $this->createStub(UserClientApi::class);
        $userStub->method('findUser')->will($this->throwException(new ClientApiException));
        $userStub->findUser(10);
        $this->expectException(ClientApiException::class);
    }
}
