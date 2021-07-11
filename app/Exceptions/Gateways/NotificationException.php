<?php

declare(strict_types=1);

namespace App\Exceptions\Gateways;

use Exception;

class NotificationException extends Exception
{
    public static function unavailable(): self
    {
        return new self(
            sprintf("Notification service is unavailable.")
        );
    }
}
