<?php

namespace App\Services\Gateways\Authorizathor;

use App\Enums\Config\Routes;
use App\Services\Gateways\Api\ClientApi;
use App\Traits\Gateways\ClientApiResponseValidatorTrait;

class AuthorizerClientApi
{
    use ClientApiResponseValidatorTrait;

    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $client;

    private $uri;

    const RESOURCE = 'Authorizathor';

    public function __construct(ClientApi $client)
    {
        $this->client = $client->createHttpClient(config('microservice.authorizer.base_uri'));
        $this->uri = Routes::AUTHORIZATHOR;
    }

    /**
     *
     * @return bool
     */
    public function authorizathor(): bool
    {
        $response = $this->client->get($this->uri);
        $this->processResponse($response, self::RESOURCE);
        return ($response->json('message') === 'Autorizado');
    }
}
