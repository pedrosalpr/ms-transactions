<?php

namespace App\Services\Gateways\Users;

use App\Entities\Users\User;
use App\Enums\Config\Routes;
use App\Services\Gateways\Api\ClientApi;
use App\Traits\Gateways\ClientApiResponseValidatorTrait;

class UserClientApi
{
    use ClientApiResponseValidatorTrait;

    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $client;

    private $uri;

    const RESOURCE = 'User';

    public function __construct(ClientApi $client)
    {
        $this->client = $client->createHttpClient(config('microservice.users.base_uri'));
        $this->uri = Routes::USERS;
    }

    /**
     * @param string $userId
     *
     * @return User
     */
    public function findUser(int $userId): User
    {
        $response = $this->client->get($this->uri . "/{$userId}");
        $this->processResponse($response, self::RESOURCE, $userId);

        return User::fromArray([
            'id' => $response->json('id'),
            'name' => $response->json('name'),
            'cpf_cnpj' => $response->json('cpf_cnpj'),
            'user_type' => $response->json('user_type'),
            'email' => $response->json('email'),
        ]);
    }
}
