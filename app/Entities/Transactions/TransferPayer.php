<?php

namespace App\Entities\Transactions;

class TransferPayer extends Transaction
{
    public static function fromArray(array $data): self
    {
        $payee = ($data['user_payee']) ? $data['user_payee']->getName() : $data['payee'];
        $description = ($data['description']) ??
            "Transfer sent to {$payee}";

        $transferPayer = new self();
        $transferPayer->value = $data['value'] * -1;
        $transferPayer->user = $data['user_payer'];
        $transferPayer->type = TransactionType::transfer();
        $transferPayer->transactionId = $data['transaction_id'];
        $transferPayer->time = $data['time'];
        $transferPayer->description = $description;
        return $transferPayer;
    }
}
