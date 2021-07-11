<?php

namespace App\Services\Transactions;

use App\Entities\Users\User;
use App\Exceptions\Gateways\AuthorizathorException;
use App\Exceptions\Gateways\ClientApiException;
use App\Exceptions\Gateways\NotificationException;
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
     * Notification service
     *
     * @var NotifyClientApi
     */
    protected NotifyClientApi $notifyClientApi;

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
     * @param NotifyClientApi $notifyClientApi
     */
    public function __construct(
        UserClientApi $userClient,
        AuthorizerClientApi $authorizatorClient,
        NotifyClientApi $notifyClientApi
    ) {
        $this->userClient = $userClient;
        $this->authorizatorClient = $authorizatorClient;
        $this->notifyClientApi = $notifyClientApi;
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
     * Notify user of transaction
     *
     * @param User $user User to be notified
     * @param string $message Message to be sent
     * @return void
     * @throws NotificationException If notification was not sent
     * @throws ClientApiException If notification resource is not found
     */
    protected function notify(User $user, string $message)
    {
        try {
            if (!$this->notifyClientApi->notify($user, $message)) {
                throw NotificationException::unavailable();
            }
        } catch (NotificationException | ClientApiException $ex) {
            // Send notification for queue
        }
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
