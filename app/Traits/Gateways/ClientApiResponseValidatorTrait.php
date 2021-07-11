<?php

declare(strict_types=1);

namespace App\Traits\Gateways;

use App\Exceptions\Gateways\ClientApiException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as ResponseStatus;

trait ClientApiResponseValidatorTrait
{
    public function processResponse(Response $response, $resource, $id = null): Response
    {
        if ($response->status() === ResponseStatus::HTTP_NOT_FOUND) {
            throw ClientApiException::notFound($resource, $id);
        }

        if (isset($response['errors'])) {
            $errors = $this->getRequestErrors($response);
            throw ClientApiException::clientApiRequestError($errors);
        }

        return tap($response)->throw();
    }

    private function getRequestErrors(Response $response): string
    {
        return (is_string($response['errors'])) ? $response['errors'] : (string) json_encode($response['errors']);
    }
}
