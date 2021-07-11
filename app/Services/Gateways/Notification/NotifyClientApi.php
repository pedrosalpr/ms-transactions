<?php

namespace App\Services\Gateways\Notification;

use App\Entities\Users\User;
use App\Enums\Config\Routes;
use App\Services\Gateways\Api\ClientApi;
use App\Traits\Gateways\ClientApiResponseValidatorTrait;

class NotifyClientApi
{
    use ClientApiResponseValidatorTrait;

    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $client;

    private $uri;

    const RESOURCE = 'Notification';

    public function __construct(ClientApi $client)
    {
        $this->client = $client->createHttpClient(config('microservice.notification.base_uri'));
        $this->uri = Routes::NOTIFICATION;
    }

    /**
     * Send a notification for user
     * @return bool
     */
    public function notify(User $user, $message): bool
    {
        $response = $this->client->get($this->uri);
        $this->processResponse($response, self::RESOURCE);
        return ($response->json('message') === 'Success');
    }
}
