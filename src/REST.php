<?php

namespace Pubq;

use Pubq\Defaults\DefaultCommonOptions;
use Pubq\Types\CommonOptions;
use Pubq\Utils\Jwt;
use Pubq\Utils\Time;

class REST
{
    private $options;

    private $http;

    private $client;

    private $version = "v1";

    public $auth;

    private $refreshTokenIntervalId;

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
            $this->startRefreshTokenInterval();
        }
    }

    public function publish(string $channel, string|array $data)
    {
        $response = $this->client->post(
            "/{$this->version}/channels/messages",
            [
                'headers' => [
                    'Authorization' => $this->auth->makeAuthorizationHeader(),
                ],
                'json' => [
                    'channel' => $channel,
                    'data' => $data,
                ],
            ]
        );

        return $response;
    }

    public function generateToken(string|null $clientId)
    {
        $response = $this->client->post(
            "/{$this->version}/keys/tokens",
            [
                'headers' => [
                    'Authorization' => $this->auth->makeAuthorizationHeader(),
                ],
                'json' => [
                    'clientId' => $clientId
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

    public function requestToken()
    {
        if ($this->options->authUrl) {
            try {
                $response = $this->client->post(
                    $this->options->authUrl,
                    [
                        'headers' => $this->options->authHeaders,
                        'json' => $this->options->authBody,
                    ],
                );

                $this->auth->setSignedAuthToken(json_decode($response->getBody())->data->token);

                return json_decode($response->getBody())->data;
            } catch (\Exception $error) {
                echo "Error in requestToken: " . $error->getMessage();
                throw $error;
            }
        }

        throw new \Exception("Auth URL has not been provided.");
    }

    public function requestRefresh()
    {
        if ($this->options->refreshUrl) {
            try {
                $body = array_merge(
                    $this->options->authBody,
                    ['token' => $this->auth->getSignedAuthToken()]
                );

                $response = $this->client->post(
                    $this->options->refreshUrl,
                    [
                        'headers' => $this->options->authHeaders,
                        'json' => $body,
                    ]
                );

                $this->auth->setSignedAuthToken(json_decode($response->getBody())->data->token);

                return json_decode($response->getBody())->data;
            } catch (\Exception $error) {
                echo "Error in requestRefresh: " . $error->getMessage();
                throw $error;
            }
        }

        throw new \Exception("Refresh URL has not been provided.");
    }

    public function requestRevoke()
    {
        if ($this->options->revokeUrl) {
            try {
                $body = array_merge(
                    $this->options->authBody,
                    ['token' => $this->auth->getSignedAuthToken()]
                );

                $response = $this->client->post(
                    $this->options->revokeUrl,
                    [
                        'headers' => $this->options->authHeaders,
                        'json' => $body,
                    ]
                );

                $this->auth->setSignedAuthToken(null);

                return $response->getBody();
            } catch (\Exception $error) {
                echo "Error in requestRevoke: " . $error->getMessage();
                throw $error;
            }
        }

        throw new \Exception("Revoke URL has not been provided.");
    }

    public function startRefreshTokenInterval()
    {
        if ($this->auth->getAuthMethod() === "Bearer") {
            $this->stopRefreshTokenInterval();

            $this->refreshTokenIntervalId = pcntl_fork();

            if ($this->refreshTokenIntervalId == -1) {
                // Fork failed
            } elseif ($this->refreshTokenIntervalId) {
                // Parent process
                // Waiting for child process
            } else {
                // Child process
                while (true) {
                    $token = $this->auth->getSignedAuthToken();
                    $authToken = Jwt::getPayload($token);

                    if ($authToken) {
                        $remainingSeconds = Time::getRemainingSeconds($authToken['exp']);
                        if ($remainingSeconds <= 60) {
                            $this->requestRefresh();
                        }
                    }

                    sleep($this->options->refreshTokenInterval);
                }
            }
        }
    }

    public function stopRefreshTokenInterval()
    {
        if ($this->refreshTokenIntervalId) {
            // Send a signal to terminate the child process
            posix_kill($this->refreshTokenIntervalId, SIGTERM);
            // Wait for the child process to exit
            pcntl_waitpid($this->refreshTokenIntervalId, $status);
            // Set the interval ID to null
            $this->refreshTokenIntervalId = null;
        }
    }
}
