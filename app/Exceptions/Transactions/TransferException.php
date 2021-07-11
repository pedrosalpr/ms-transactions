<?php

declare(strict_types=1);

namespace App\Exceptions\Transactions;

use Exception;

class TransferException extends Exception
{
    public static function userNotAllowed($name): self
    {
        return new self(
            sprintf(
                "User %s not allowed to execute the transfer",
                $name
            )
        );
    }

    public static function insufficientBalance()
    {
        return new self(
            sprintf(
                "Insufficient balance to transfer"
            )
        );
    }

    public function report()
    {
        return [
            'message' => $this->message
        ];
    }
}
