<?php

namespace App\Contracts\Transactions;

interface TransactionContract
{
    public function transact(array $data);

    public function getMessage();
}
