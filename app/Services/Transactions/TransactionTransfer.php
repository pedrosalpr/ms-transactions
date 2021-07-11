<?php

namespace App\Services\Transactions;

use App\Contracts\Transactions\TransactionContract;
use App\Entities\Transactions\TransferPayee;
use App\Entities\Transactions\TransferPayer;
use App\Entities\Users\User;
use App\Enums\Users\UserType;
use App\Exceptions\Transactions\TransferException;
use App\Models\Transaction as TransactionModel;
use App\Repositories\Account;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransactionTransfer extends Transaction implements TransactionContract
{

    /**
     * Make the transfer transaction
     *
     * @param array $data The data that was sent
     * @return void
     */
    public function transact(array $data)
    {
        $userPayer = $this->userClient->findUser($data['payer']);
        $userPayee = $this->userClient->findUser($data['payee']);
        $value = (float) Arr::get($data, 'value');
        $this->checkPayerTransfer($userPayer);
        $this->checkPayerBalance($userPayer, $value);
        $this->transfer($userPayer, $userPayee, $data);
        $this->setMessage($value);
        $this->notify($userPayee, $this->getMessage());
    }

    /**
     * Check if the payer is a shopkeeper type
     *
     * @param User $userPayer The payer who will make the transfer
     * @return void
     * @throws TransferException If the payer is a shopkeeper type
     */
    private function checkPayerTransfer(User $userPayer)
    {
        if ($userPayer->getUserType() == UserType::SHOPKEEPER) {
            throw TransferException::userNotAllowed($userPayer->getName());
        }
    }

    /**
     * Check if the payer has enough balance to make the transfer
     *
     * @param User $userPayer The payer who will make the transfer
     * @param float $value The value to be transferred
     * @return void
     * @throws TransferException If the payer does not have enough balance
     */
    private function checkPayerBalance(User $userPayer, float $value): void
    {
        $balance = (new Account($userPayer))->getBalance();
        if ($value >= $balance) {
            throw TransferException::insufficientBalance();
        }
    }

    /**
     * Made the transfer between the payer and the payee
     *
     * @param User $userPayer The payer who will make the transfer
     * @param User $userPayee The payee who will receive the transfer
     * @param array $data The data that was sent
     * @return void
     */
    private function transfer(User $userPayer, User $userPayee, array $data): void
    {
        $data = array_merge($data, [
            'user_payee' => $userPayee,
            'user_payer' => $userPayer,
            'time' => Carbon::now(),
            'transaction_id' => Str::orderedUuid()->toString(),
        ]);
        $transferPayer = TransferPayer::fromArray($data);
        $transferPayee = TransferPayee::fromArray($data);
        TransactionModel::create($transferPayer->toArray());
        TransactionModel::create($transferPayee->toArray());
    }

    /**
     * Set a message
     *
     * @param float $value
     * @return void
     */
    protected function setMessage($value): void
    {
        $this->message = "Transfer in the value of {$value} successfully made";
    }
}
