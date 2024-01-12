<?php

use PHPUnit\Framework\TestCase;
use Pubq\REST;

class TokenTest extends TestCase
{
    private $rest;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the REST class with the mock HTTP client
        // Replace the values with your actual URLs
        $this->rest = new REST([
            'authUrl' => 'FULL_TOKEN_GENERATE_URL',
            'refreshUrl' => 'FULL_TOKEN_REFRESH_URL',
            'revokeUrl' => 'FULL_TOKEN_REVOKE_URL',
        ]);
    }

    public function testRequestToken()
    {
        $response = $this->rest->auth->requestToken();

        $this->assertIsObject($response);

        $this->assertIsString($this->rest->auth->getSignedAuthToken());
    }

    public function testRefreshToken()
    {
        $this->rest->auth->requestToken();

        $response = $this->rest->auth->requestRefresh();

        $this->assertIsObject($response);
    }

    public function testRevokeToken()
    {
        $this->rest->auth->requestToken();

        $response = $this->rest->auth->requestRevoke();

        $this->assertIsObject($response);
    }
}
