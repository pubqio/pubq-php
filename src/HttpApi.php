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
            'base_uri' => 'https://rest.pubq.io',
        ]);
    }

    public function publish(string $channel, string|array $data)
    {
        $response = $this->httpClient->post('/v1/messages/publish', [
            'headers' => [
                'Id' => $this->applicationId,
                'Key' => $this->applicationKey,
                'Secret' => $this->applicationSecret,
            ],
            'json' => [
                'channel' => $channel,
                'data' => $data,
            ],
        ]);

        return $response;
    }
}
