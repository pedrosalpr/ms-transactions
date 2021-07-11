<?php

declare(strict_types=1);

namespace App\Entities\Transactions;

class TransactionType
{
    private const TRANSFER = 1;
    private const DEPOSIT = 2;

    private $type;

    private function __construct(int $type)
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return (string) $this->type;
    }

    public function getValue(): int
    {
        return $this->type;
    }

    public static function transfer(): self
    {
        return new self(static::TRANSFER);
    }

    public static function deposit(): self
    {
        return new self(static::DEPOSIT);
    }
}
