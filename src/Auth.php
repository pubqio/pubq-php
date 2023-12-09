<?php

namespace Pubq;

use Pubq\Types\CommonOptions;

class Auth
{
    /**
     * @var CommonOptions
     */
    private $options;

    public $signedAuthToken;

    public function __construct(CommonOptions $options)
    {
        $this->options = $options;
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
}
