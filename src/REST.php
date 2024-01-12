<?php

namespace Pubq;

use Pubq\Defaults\DefaultCommonOptions;
use Pubq\Types\CommonOptions;

class REST
{
    private $options;

    private $http;

    private $client;

    private $version = "v1";

    public $auth;

    public function __construct(array $options = [])
    {
        $this->options = new CommonOptions(
            array_merge(
                DefaultCommonOptions::get(),
                $options
            )
        );

        $this->http = new Http();

        $this->client = $this->http->getClient();

        $this->auth = new Auth($this->options);

        if ($this->options->autoRefreshToken) {
            $this->auth->startRefreshTokenInterval();
        }
    }

    public function publish(string $channel, string|array $data)
    {
        $response = $this->client->post(
            "/{$this->version}/channels/{$channel}/messages",
            [
                'headers' => [
                    'Authorization' => $this->auth->makeAuthorizationHeader(),
                ],
                'json' => [
                    'data' => $data,
                ],
            ]
        );

        return $response;
    }

    public function generateToken(array $options = [])
    {
        $response = $this->client->post(
            "/{$this->version}/keys/tokens",
            [
                'headers' => [
                    'Authorization' => $this->auth->makeAuthorizationHeader(),
                ],
                'json' => [
                    'clientId' => $options['clientId'] ?? null,
                ],
            ]
        );

        return $response;
    }

    public function refreshToken(string $token)
    {
        $response = $this->client->post(
            "/{$this->version}/keys/tokens/refresh",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]
        );

        return $response;
    }

    public function revokeToken(string $token)
    {
        $response = $this->client->post(
            "/{$this->version}/keys/tokens/revoke",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]
        );

        return $response;
    }
}
