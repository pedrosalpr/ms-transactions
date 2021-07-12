<?php

namespace Tests\Feature\Account;

use App\Enums\Config\Routes;
use App\Enums\Users\UserType;
use App\Services\Gateways\Users\UserClientApi;
use App\Services\Users\UserAccount;
use Database\Factories\TransactionDepositFactory;
use Database\Factories\TransactionTransferFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use DatabaseMigrations;

    public function testShouldReturnTheBalanceOfZeroWithoutAnyTransactions()
    {
        $accountId = 10;
        $value = 0;
        $userPayer = $this->mockUserAccount($accountId, UserType::COMMON);
        $response = $this->getJson(Routes::BALANCE.'/'.$accountId);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(["value" => $value]);
    }

    public function testShouldReturnTheBalanceAfterAnyTransaction()
    {
        $accountId = 10;
        $value = 2;
        $accountTransactionDeposit = (new TransactionDepositFactory)->create([
            'user_id' => $accountId,
            'value' => $value
        ]);
        $userAccount = $this->mockUserAccount($accountId, UserType::COMMON);
        $response = $this->getJson(Routes::BALANCE.'/'.$accountId);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(["value" => $value]);
    }

    public function testShouldReturnTheBalanceSubtractedAfterTheDepositWithTransfer()
    {
        $accountId = 10;
        $valueDeposit = 10;
        $valueTransfer = 5;
        $valueTotal = $valueDeposit - $valueTransfer;
        $accountTransactionDeposit = (new TransactionDepositFactory)->create([
            'user_id' => $accountId,
            'value' => $valueDeposit
        ]);
        $accountTransactionDeposit = (new TransactionTransferFactory)->create([
            'user_id' => $accountId,
            'value' => $valueTransfer*-1
        ]);
        $userAccount = $this->mockUserAccount($accountId, UserType::COMMON);
        $response = $this->getJson(Routes::BALANCE.'/'.$accountId);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(["value" => $valueTotal]);
    }

    private function mockUserAccount(int $id, int $userType)
    {
        $userFactory = (new UserFactory)->make(['id' => $id, 'user_type' => $userType]);
        $this->mock(UserClientApi::class, function (MockInterface $mock) use ($userFactory) {
            $mock->shouldReceive('findUser')->andReturn($userFactory);
        });
        $mockUser = $this->createMock(UserAccount::class);
        $mockUser->method('getEntity')->with($this->equalTo($userFactory));
        return $userFactory;
    }


}
