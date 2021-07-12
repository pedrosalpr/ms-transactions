<?php

namespace Tests\Feature\Transactions;

use App\Enums\Config\Routes;
use App\Enums\Users\UserType;
use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use App\Services\Gateways\Users\UserClientApi;
use App\Services\Transactions\Transaction;
use App\Services\Users\UserPayee;
use App\Services\Users\UserPayer;
use Database\Factories\TransactionDepositFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use DatabaseMigrations;

    private $userMock;
    private $authorizatorMock;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->userMock = $this->createMock(UserClientApi::class);
        // $this->authorizatorMock = $this->createMock(AuthorizerClientApi::class);
        // $this->userMock = Mockery::mock(UserClientApi::class);
        // $this->authorizatorMock = Mockery::mock(AuthorizerClientApi::class);
        // $this->authorizatorMock->shouldReceive('authorizathor')->andReturn(true);
    }

    /**
     *
     *
     * @return void
     */
    // public function testShouldReturnMicroserviceUser()
    // {
    //     $userId = 1;
    //     $userFactory = $this->mockUser($userId, UserType::COMMON);
    //     $this->assertEquals($userFactory, $this->userMock->findUser($userId));
    // }

    /**
     *
     *
     * @return void
     */
    public function testShouldTransferValueFromCommonUserToShopkeeperUser()
    {
        $payeeId = 10;
        $payerId = 5;
        $value = 2;
        $data = [
            'payer' => $payerId,
            'payee' => $payeeId,
            'value' => $value
        ];
        $payerTransaction = (new TransactionDepositFactory)->create([
            'user_id' => $payerId,
            'value' => 15
        ]);
        $this->mockUserApiClient($payerId);
        $this->mockUserPayer($payerId);
        $this->mockUserPayee($payeeId);
        $this->mockAuthorized();
        $this->mockNotifier();
        $response = $this->postJson(Routes::TRANSFER, $data);
        $response->assertStatus(Response::HTTP_OK);
    }

    private function mockUserApiClient(int $id)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => UserType::COMMON]);
        $this->mock(UserClientApi::class, function (MockInterface $mock) use ($userFactory) {
            $mock->shouldReceive('findUser')->andReturn($userFactory);
        });
    }

    private function mockUserPayer(int $id)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => UserType::COMMON]);
        $mockUser = $this->createMock(UserPayer::class);
        $mockUser->method('getEntity')->with($this->equalTo($userFactory));
    }

    private function mockUserPayee(int $id)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => UserType::SHOPKEEPER]);
        $mockUser = $this->createMock(UserPayee::class);
        $mockUser->method('getEntity')->with($this->equalTo($userFactory));
    }

    private function mockAuthorized()
    {
        $this->mock(AuthorizerClientApi::class, function (MockInterface $mock) {
            $mock->shouldReceive('authorizathor')->andReturn(true);
        });
    }

    private function mockNotifier()
    {
        $this->mock(Transaction::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods('notify');
        });
    }
}
