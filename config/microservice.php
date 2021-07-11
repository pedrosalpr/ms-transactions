<?php

declare(strict_types=1);

return [
    'authorizer' => [
        'base_uri' => env('MS_AUTHORIZER_BASE_URI', ''),
    ],
    'notification' => [
        'base_uri' => env('MS_NOTIFICATION_BASE_URI', ''),
    ],
    'users' => [
        'base_uri' => env('MS_USERS_API_BASE_URI', ''),
    ],
];
