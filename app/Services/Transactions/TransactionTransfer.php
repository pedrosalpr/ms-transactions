<?php

namespace App\Services\Transactions;

use App\Contracts\Transactions\TransactionContract;
use App\Entities\Transactions\TransferPayee;
use App\Entities\Transactions\TransferPayer;
use App\Enums\Users\UserType;
use App\Exceptions\Transactions\TransferException;
use App\Models\Transaction as TransactionModel;
use App\Repositories\Account;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransactionTransfer extends Transaction implements TransactionContract
{

    /**
     * Make the transfer transaction
     *
     * @param float $value The data that was sent
     * @return void
     */
    public function transact(float $value)
    {
        $this->checkPayerTransfer();
        $this->checkPayerBalance($value);
        $this->transfer($value);
        $this->setMessage($value);
        $this->notify($this->userPayee, $this->getMessage());
    }

    /**
     * Check if the payer is a shopkeeper type
     *
     * @return void
     * @throws TransferException If the payer is a shopkeeper type
     */
    private function checkPayerTransfer()
    {
        if ($this->userPayer->getUserType() == UserType::SHOPKEEPER) {
            throw TransferException::userNotAllowed($this->userPayer->getName());
        }
    }

    /**
     * Check if the payer has enough balance to make the transfer
     *
     * @param float $value The value to be transferred
     * @return void
     * @throws TransferException If the payer does not have enough balance
     */
    private function checkPayerBalance(float $value): void
    {
        $balance = (new Account($this->userPayee))->getBalance();
        if ($value >= $balance) {
            throw TransferException::insufficientBalance();
        }
    }

    /**
     * Made the transfer between the payer and the payee
     *
     * @param float $value
     * @return void
     */
    private function transfer(float $value): void
    {
        $data = [
            'value' => $value,
            'user_payee' => $this->userPayee,
            'user_payer' => $this->userPayer,
            'time' => Carbon::now(),
            'transaction_id' => Str::orderedUuid()->toString(),
        ];
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
