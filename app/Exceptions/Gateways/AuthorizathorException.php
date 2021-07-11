<?php

declare(strict_types=1);

namespace App\Exceptions\Gateways;

use Exception;

class AuthorizathorException extends Exception
{
    public static function notAllowed(): self
    {
        return new self(
            sprintf("Authorization not allowed")
        );
    }

    public function report()
    {
        return [
            "message" => $this->message
        ];
    }
}
