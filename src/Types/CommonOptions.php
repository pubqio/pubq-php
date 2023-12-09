<?php

namespace Pubq\Types;

class CommonOptions
{
    /**
     * @var string|null
     */
    public $key;

    /**
     * @var string|null
     */
    public $authUrl;

    /**
     * @var string|null
     */
    public $refreshUrl;

    /**
     * @var string|null
     */
    public $revokeUrl;

    /**
     * @var array
     */
    public $authBody;

    /**
     * @var array
     */
    public $authHeaders;

    /**
     * @var bool
     */
    public $autoAuthenticate;

    /**
     * @var bool
     */
    public $autoRefreshToken;

    /**
     * @var int
     */
    public $refreshTokenInterval;

    /**
     * @return self
     */
    public function __construct(array $options = [])
    {
        if (isset($options['key'])) {
            $this->key = $options['key'];
        }

        if (isset($options['authUrl'])) {
            $this->authUrl = $options['authUrl'];
        }

        if (isset($options['refreshUrl'])) {
            $this->refreshUrl = $options['refreshUrl'];
        }

        if (isset($options['revokeUrl'])) {
            $this->revokeUrl = $options['revokeUrl'];
        }

        if (isset($options['authBody'])) {
            $this->authBody = $options['authBody'];
        }

        if (isset($options['authHeaders'])) {
            $this->authHeaders = $options['authHeaders'];
        }

        if (isset($options['autoAuthenticate'])) {
            $this->autoAuthenticate = $options['autoAuthenticate'];
        }

        if (isset($options['autoRefreshToken'])) {
            $this->autoRefreshToken = $options['autoRefreshToken'];
        }

        if (isset($options['refreshTokenInterval'])) {
            $this->refreshTokenInterval = $options['refreshTokenInterval'];
        }

        return $this;
    }
}
