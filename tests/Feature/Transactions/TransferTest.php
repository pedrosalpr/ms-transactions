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
        $userPayer = $this->mockUserPayer($payerId, UserType::COMMON);
        $userPayee = $this->mockUserPayee($payeeId, UserType::SHOPKEEPER);
        $this->mockAuthorized();
        $this->mockNotifier();
        $response = $this->postJson(Routes::TRANSFER, $data);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testShouldTransferValueFromCommonUserToOtherCommonUser()
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
        $userPayer = $this->mockUserPayer($payerId, UserType::COMMON);
        $userPayee = $this->mockUserPayee($payeeId, UserType::COMMON);
        $this->mockAuthorized();
        $this->mockNotifier();
        $response = $this->postJson(Routes::TRANSFER, $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(["message" => "Transfer in the value of {$value} successfully made"]);
    }

    public function testShouldReturnWithInsufficientBalance()
    {
        $payeeId = 10;
        $payerId = 5;
        $value = 2;
        $data = [
            'payer' => $payerId,
            'payee' => $payeeId,
            'value' => $value
        ];
        $userPayer = $this->mockUserPayer($payerId, UserType::COMMON);
        $userPayee = $this->mockUserPayee($payeeId, UserType::SHOPKEEPER);
        $this->mockAuthorized();
        $this->mockNotifier();
        $response = $this->postJson(Routes::TRANSFER, $data);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(["message" => "Insufficient balance to transfer"]);
    }

    public function testShouldReturnThatThePayerIsNotAllowedToTransfer()
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
        $userPayer = $this->mockUserPayer($payerId, UserType::SHOPKEEPER);
        $userPayee = $this->mockUserPayee($payeeId, UserType::SHOPKEEPER);
        $this->mockAuthorized();
        $this->mockNotifier();
        $response = $this->postJson(Routes::TRANSFER, $data);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(["message" => "User {$userPayer->getName()} not allowed to execute the transfer"]);
    }

    private function mockUserPayer(int $id, int $userType)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => $userType]);
        $this->mock(UserClientApi::class, function (MockInterface $mock) use ($userFactory) {
            $mock->shouldReceive('findUser')->andReturn($userFactory);
        });
        $mockUser = $this->createMock(UserPayer::class);
        $mockUser->method('getEntity')->with($this->equalTo($userFactory));
        return $userFactory;
    }

    private function mockUserPayee(int $id, int $userType)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => $userType]);
        $mockUser = $this->createMock(UserPayee::class);
        $mockUser->method('getEntity')->with($this->equalTo($userFactory));
        return $userFactory;
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
