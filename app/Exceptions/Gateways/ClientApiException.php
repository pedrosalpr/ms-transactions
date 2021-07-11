<?php

declare(strict_types=1);

namespace App\Exceptions\Gateways;

use Exception;

class ClientApiException extends Exception
{
    public static function clientApiRequestError(string $errors): ClientApiException
    {
        return new self(
            sprintf("Client Api request error\n'%s'", $errors)
        );
    }

    public static function notFound(string $client, $id): ClientApiException
    {
        return new self(
            sprintf("%s %s not found", $client, $id)
        );
    }

    public function report()
    {
        return [
            "message" => $this->message
        ];
    }
}
