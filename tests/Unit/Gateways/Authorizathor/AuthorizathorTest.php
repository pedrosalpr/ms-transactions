<?php

namespace Tests\Unit\Gateways\Authorizathor;

use App\Services\Gateways\Authorizathor\AuthorizerClientApi;
use Tests\TestCase;

class AuthorizathorTest extends TestCase
{
    public function testShouldReturnTrueForSuccessfulAuthorization()
    {

        $userMock = $this->createMock(AuthorizerClientApi::class);
        $userMock->method('authorizathor')->willReturn(true);
        $this->assertEquals(true, $userMock->authorizathor());
    }
}
