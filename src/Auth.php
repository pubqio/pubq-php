<?php

namespace Pubq;

use Pubq\Types\CommonOptions;
use Pubq\Utils\Jwt;
use Pubq\Utils\Time;

class Auth
{
    /**
     * @var CommonOptions
     */
    private $options;

    private $http;

    private $client;

    private $signedAuthToken;

    private $refreshTokenIntervalId;

    public function __construct(CommonOptions $options)
    {
        $this->options = $options;

        $this->http = new Http();

        $this->client = $this->http->getClient();
    }

    public function getAuthMethod()
    {
        if ($this->options->authUrl) {
            return 'Bearer';
        } elseif ($this->options->key) {
            return 'Basic';
        }

        return false;
    }

    private function getKeyOrToken()
    {
        if ($this->options->authUrl) {
            return $this->getSignedAuthToken();
        } elseif ($this->options->key) {
            return $this->getKeyBase64();
        }

        return false;
    }

    public function getKey()
    {
        if ($this->options->key) {
            return $this->options->key;
        }

        throw new \Exception('API key has not been specified.');
    }

    public function getKeyBase64()
    {
        return base64_encode($this->getKey());
    }

    public function makeAuthorizationHeader()
    {
        if ($this->getAuthMethod() && $this->getKeyOrToken()) {
            return $this->getAuthMethod() . ' ' . $this->getKeyOrToken();
        }

        throw new \Exception('Auth method has not been specified.');
    }

    public function getSignedAuthToken()
    {
        return $this->signedAuthToken;
    }

    public function setSignedAuthToken(string|null $token)
    {
        $this->signedAuthToken = $token;
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

                $this->setSignedAuthToken(json_decode($response->getBody())->data->token);

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
                    ['token' => $this->getSignedAuthToken()]
                );

                $response = $this->client->post(
                    $this->options->refreshUrl,
                    [
                        'headers' => $this->options->authHeaders,
                        'json' => $body,
                    ]
                );

                $this->setSignedAuthToken(json_decode($response->getBody())->data->token);

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
                    ['token' => $this->getSignedAuthToken()]
                );

                $response = $this->client->post(
                    $this->options->revokeUrl,
                    [
                        'headers' => $this->options->authHeaders,
                        'json' => $body,
                    ]
                );

                $this->setSignedAuthToken(null);

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
        if ($this->getAuthMethod() === "Bearer") {
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
                    $token = $this->getSignedAuthToken();
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
