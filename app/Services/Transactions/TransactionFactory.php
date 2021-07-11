<?php

namespace App\Services\Transactions;

use App\Entities\Transactions\TransactionType;
use App\Contracts\Transactions\TransactionContract;
use App\Exceptions\Transactions\TransactionEntityException;
use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use App\Services\Gateways\Notification\NotifyClientApi;
use App\Services\Gateways\Users\UserClientApi;

class TransactionFactory
{

    /**
     * Return transaction type object
     *
     * @param TransactionType $type
     * @return TransactionContract
     * @throws TransactionEntityException If the transaction type is invalid
     */
    public static function getTransactionType(TransactionType $type): TransactionContract
    {
        switch ($type) {
            case TransactionType::transfer():
                return resolve(TransactionTransfer::class, self::getParameters());
                break;

            case TransactionType::deposit():
                return resolve(TransactionDeposit::class, self::getParameters());
                break;

            default:
                throw TransactionEntityException::invalidParameterType(
                    'type',
                    TransactionType::class,
                    gettype($type)
                );
        }
    }

    /**
     * Return transaction parameters
     *
     * @return array
     */
    private static function getParameters(): array
    {
        return [
            'userClient' => resolve(UserClientApi::class),
            'authorizatorClient' => resolve(AuthorizerClientApi::class)
        ];
    }
}
