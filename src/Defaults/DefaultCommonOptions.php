<?php

namespace Pubq\Defaults;

class DefaultCommonOptions
{
    public static function get(): array
    {
        return [
            "key" => null,

            "authUrl" => null,
            "refreshUrl" => null,
            "revokeUrl" => null,

            "authBody" => [],
            "authHeaders" => [],

            "autoAuthenticate" => true,
            "autoRefreshToken" => true,
            "refreshTokenInterval" => 1,
        ];
    }
}
