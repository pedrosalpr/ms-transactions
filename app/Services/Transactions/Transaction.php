<?php

namespace App\Services\Transactions;

use App\Entities\Users\User;
use App\Exceptions\Gateways\AuthorizathorException;
use App\Exceptions\Gateways\ClientApiException;
use App\Exceptions\Gateways\NotificationException;
use App\Jobs\TransactionNotifierJob;
use App\Models\Transaction as TransactionModel;
use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use App\Services\Gateways\Notification\NotifyClientApi;
use App\Services\Gateways\Users\UserClientApi;


abstract class Transaction
{
    /**
     * User service
     *
     * @var UserClientApi
     */
    protected UserClientApi $userClient;

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

    /**
     * Abstract class of the transaction, which has authorization and notification service and the message to be returned
     *
     * @param UserClientApi $userClient
     * @param AuthorizerClientApi $authorizatorClient
     */
    public function __construct(
        UserClientApi $userClient,
        AuthorizerClientApi $authorizatorClient
    ) {
        $this->userClient = $userClient;
        $this->authorizatorClient = $authorizatorClient;
    }

    abstract protected function setMessage(float $value);

    /**
     * Check if the service is authorized to proceed with the transaction
     *
     * @return void
     * @throws AuthorizathorException If the service is not authorized
     */
    protected function checkAuthorizathor()
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
    protected function notify(User $user, string $message)
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
}
