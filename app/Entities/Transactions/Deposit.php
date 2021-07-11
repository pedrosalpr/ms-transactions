<?php

declare(strict_types=1);

namespace App\Entities\Transactions;

use Illuminate\Support\Carbon;

class Deposit extends Transaction
{
    public static function fromArray(array $data): self
    {
        $deposit = new self();

        $value = $data['value'];

        $deposit->value = $value;
        $deposit->user = $data['user'];
        $deposit->type = TransactionType::deposit();
        $deposit->transactionId = $data['transaction_id'];
        $deposit->time = $data['time'] ?? Carbon::now();
        $deposit->description = $data['description'] ?? "Deposit in the amount of {$value}";

        return $deposit;
    }
}
