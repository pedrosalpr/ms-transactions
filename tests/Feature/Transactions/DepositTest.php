<?php

namespace Tests\Feature\Transactions;

use App\Entities\Transactions\TransactionType;
use App\Enums\Config\Routes;
use App\Enums\Config\Tables;
use App\Enums\Users\UserType;
use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use App\Services\Gateways\Users\UserClientApi;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class DepositTest extends TestCase
{
    use DatabaseMigrations;


    public function testShouldDepositMoneyIntoTheUsersAccount()
    {
        $payeeId = 10;
        $value = 10;
        $data = [
            'payee' => $payeeId,
            'value' => $value
        ];

        $this->mockUser($payeeId, UserType::COMMON);
        $this->mockAuthorized();
        $response = $this->postJson(Routes::DEPOSIT, $data);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas(Tables::TRANSACTIONS, [
            'user_id' => $payeeId,
            'value' => $value,
            'type' => TransactionType::deposit()->getValue()
        ]);
    }

    public function testShouldDepositNotAuthorized()
    {
        $payeeId = 10;
        $value = 10;
        $data = [
            'payee' => $payeeId,
            'value' => $value
        ];

        $this->mockUser($payeeId, UserType::COMMON);
        $this->mockNotAuthorized();
        $response = $this->postJson(Routes::DEPOSIT, $data);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    private function mockUser(int $id, int $userType)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'userType' => $userType]);
        $this->mock(UserClientApi::class, function (MockInterface $mock) use ($userFactory) {
            $mock->shouldReceive('findUser')->andReturn($userFactory);
        });
    }

    private function mockAuthorized()
    {
        $this->mock(AuthorizerClientApi::class, function (MockInterface $mock) {
            $mock->shouldReceive('authorizathor')->once()->andReturn(true);
        });
    }

    private function mockNotAuthorized()
    {
        $this->mock(AuthorizerClientApi::class, function (MockInterface $mock) {
            $mock->shouldReceive('authorizathor')->andReturn(false);
        });
    }
}
