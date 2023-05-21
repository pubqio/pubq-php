<?php

namespace Pubq;

use GuzzleHttp\Client;

class HttpApi
{
    private $applicationId;
    private $applicationKey;
    private $applicationSecret;
    private $httpClient;

    public function __construct($applicationId, $applicationKey, $applicationSecret)
    {
        $this->applicationId = $applicationId;
        $this->applicationKey = $applicationKey;
        $this->applicationSecret = $applicationSecret;

        $this->httpClient = new Client([
            'base_uri' => 'https://api.pubq.io',
        ]);
    }

    public function publish(string $channel, string|array $data)
    {
        $response = $this->httpClient->post('/v1/messages/publish', [
            'headers' => [
                'id' => $this->applicationId,
                'key' => $this->applicationKey,
                'secret' => $this->applicationSecret,
            ],
            'json' => [
                'channel' => $channel,
                'data' => $data,
            ],
        ]);

        return $response;
    }
}
