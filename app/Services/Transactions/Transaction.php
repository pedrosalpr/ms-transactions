<?php

namespace App\Services\Transactions;

use App\Entities\Users\User;
use App\Exceptions\Gateways\AuthorizathorException;
use App\Jobs\TransactionNotifierJob;
use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use App\Services\Gateways\Users\UserClientApi;

abstract class Transaction
{

    /**
     * Authorization service
     *
     * @var AuthorizerClientApi
     */
    protected AuthorizerClientApi $authorizatorClient;

    /**
     * Message to be sent
     *
     * @var string
     */
    protected string $message;

    protected User $userPayee;

    protected User $userPayer;

    /**
     * Abstract class of the transaction, which has authorization and the message to be returned
     *
     * @param UserClientApi $userClient
     * @param AuthorizerClientApi $authorizatorClient
     */
    public function __construct(AuthorizerClientApi $authorizatorClient)
    {
        $this->authorizatorClient = $authorizatorClient;
    }

    abstract protected function setMessage(float $value): void;

    /**
     * Check if the service is authorized to proceed with the transaction
     *
     * @return void
     * @throws AuthorizathorException If the service is not authorized
     */
    protected function checkAuthorizathor(): void
    {
        if (!$this->authorizatorClient->authorizathor()) {
            throw AuthorizathorException::notAllowed();
        }
    }

    /**
     * Dispatch job notify user of transaction
     *
     * @param User $user User to be notified
     * @param string $message Message to be sent
     * @return void
     */
    protected function notify(User $user, string $message): void
    {
        TransactionNotifierJob::dispatch($user, $message);
    }

    /**
     * Return a message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function setUserPayer(User $userPayer): void
    {
        $this->userPayer = $userPayer;
    }

    public function setUserPayee(User $userPayee): void
    {
        $this->userPayee = $userPayee;
    }
}
