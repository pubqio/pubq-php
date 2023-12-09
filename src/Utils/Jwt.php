<?php

namespace Pubq\Utils;

class Jwt
{
    public static function getPayload(string|null $token)
    {
        return json_decode(base64_decode(explode('.', $token)[1]));
    }
}
