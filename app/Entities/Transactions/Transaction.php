<?php

declare(strict_types=1);

namespace App\Entities\Transactions;

use App\Contracts\Transactions\TransactionTypeContract;
use App\Entities\Users\User;
use Illuminate\Support\Carbon;

class Transaction implements TransactionTypeContract
{
    protected User $user;

    protected float $value;

    protected string $transactionId;

    protected Carbon $time;

    protected TransactionType $transactionType;

    protected string $description;

    public function getUser(): User
    {
        return $this->user;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getTime(): Carbon
    {
        return $this->time;
    }

    public function getTransactionType(): TransactionType
    {
        return $this->transactionType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray()
    {
        return [
            'transaction_id' => $this->transactionId,
            'user_id' => (int) $this->user->getId(),
            'value' => (float) $this->value,
            'type' => $this->type->getValue(),
            'time' => $this->time,
            'description' => $this->description
        ];
    }
}
