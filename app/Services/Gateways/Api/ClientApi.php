<?php

declare(strict_types=1);

namespace App\Services\Gateways\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ClientApi
{
    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function createHttpClient($baseUri): PendingRequest
    {
        return Http::baseUrl($baseUri)
            ->acceptJson()->contentType('application/json');
    }
}
