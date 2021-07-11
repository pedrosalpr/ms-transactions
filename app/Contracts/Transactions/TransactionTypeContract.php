<?php

namespace App\Contracts\Transactions;

use App\Entities\Transactions\TransactionType;
use App\Entities\Users\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

interface TransactionTypeContract extends Arrayable
{
    public function getTransactionId(): string;

    public function getUser(): User;

    public function getTransactionType(): TransactionType;

    public function getValue(): float;

    public function getTime(): Carbon;

    public function getDescription(): string;
}
