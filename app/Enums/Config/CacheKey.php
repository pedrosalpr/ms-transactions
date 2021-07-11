<?php

namespace App\Enums\Config;

final class CacheKey
{
    private const USERS = 'user_';

    public static function getKeyCacheUserById($id)
    {
        return self::USERS.'_'.$id;
    }
}
