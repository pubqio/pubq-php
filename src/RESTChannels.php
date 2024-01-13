<?php

namespace Pubq;

use ErrorListener;

class RESTChannels
{
    private $http;

    private $client;

    private $version = "v1";

    public $auth;

    private $channel = null;

    public function __construct(Auth $auth)
    {
        $this->http = new Http();

        $this->client = $this->http->getClient();

        $this->auth = $auth;
    }

    public function get(string $channelName)
    {
        $this->channel = $channelName;

        return $this;
    }

    // Overload 1: publish(string $event, $data, ErrorListener $listener)

    // Overload 2: publish(array $events, $data, ErrorListener $listener)

    // Overload 3: publish(array $messages, ErrorListener $listener)

    // Overload 4: publish($data, ErrorListener $listener)

    // Overload 5: publish($data)

    // Overload 6: publish(array $messages)

    public function publish(string | array $arg1, $arg2 = null, ErrorListener $arg3 = null)
    {
        if (is_string($arg1) && is_callable($arg3)) {
            // Overload 1
        } elseif (is_array($arg1) && isset($arg2) && is_callable($arg3)) {
            // Overload 2
        } elseif (is_array($arg1) && is_callable($arg2)) {
            // Overload 3
        } elseif (is_array($arg1) && !isset($arg2)) {
            // Overload 6
        } elseif (!isset($arg2)) {
            // Overload 5
            $this->client->post(
                "/{$this->version}/channels/{$this->channel}/messages",
                [
                    'headers' => [
                        'Authorization' => $this->auth->makeAuthorizationHeader(),
                    ],
                    'json' => [
                        'data' => $arg1,
                    ],
                ]
            );
        } else {
            // Overload 4
        }
    }
}
