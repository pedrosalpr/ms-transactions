<?php

namespace App\Services\Transactions;

use App\Contracts\Transactions\TransactionContract;
use App\Entities\Transactions\Deposit;
use App\Entities\Users\User;
use App\Models\Transaction as TransactionModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransactionDeposit extends Transaction implements TransactionContract
{
    public function transact(float $value): void
    {
        $this->checkAuthorizathor();
        $this->deposit($value);
        $this->setMessage($value);
        $this->notify($this->userPayee, $this->getMessage());
    }

    private function deposit(float $value): void
    {
        $data = [
            'value' => $value,
            'user' => $this->userPayee,
            'time' => Carbon::now(),
            'transaction_id' => Str::orderedUuid()->toString(),
        ];
        $deposit = Deposit::fromArray($data);
        TransactionModel::create($deposit->toArray());
    }

    /**
     * Set a message
     *
     * @param float $value
     * @return void
     */
    protected function setMessage(float $value): void
    {
        $this->message = "Successfully deposited {$value} into your account";
    }
}
