<?php

namespace Pubq;

use GuzzleHttp\Client;

class REST
{
    private $applicationKey;

    private $httpClient;

    public function __construct($applicationKey)
    {
        $this->applicationKey = $applicationKey;

        $this->httpClient = new Client([
            'base_uri' => 'https://rest.pubq.io',
        ]);
    }

    public function publish(string $channel, string|array $data)
    {
        $response = $this->httpClient->post('/v1/messages/publish', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->applicationKey),
            ],
            'json' => [
                'channel' => $channel,
                'data' => $data,
            ],
        ]);

        return $response;
    }
}
