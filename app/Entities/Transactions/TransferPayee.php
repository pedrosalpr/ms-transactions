<?php

namespace App\Entities\Transactions;

class TransferPayee extends Transaction
{
    public static function fromArray(array $data): self
    {
        $payer = ($data['user_payer']) ? $data['user_payer']->getName() : $data['payer'];
        $description = ($data['description']) ??
            "Transfer received from {$payer}";

        $transferPayee = new self();
        $transferPayee->value = $data['value'];
        $transferPayee->user = $data['user_payee'];
        $transferPayee->type = TransactionType::transfer();
        $transferPayee->transactionId = $data['transaction_id'];
        $transferPayee->time = $data['time'];
        $transferPayee->description = $description;
        return $transferPayee;
    }
}
