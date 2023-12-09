<?php

namespace Pubq;

use GuzzleHttp\Client;

class Http
{
    private $baseUrl = 'https://rest.pubq.io';

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    public function getClient()
    {
        return $this->client;
    }
}
