<?php

use PHPUnit\Framework\TestCase;
use Pubq\REST;

class RESTTest extends TestCase
{
    private $applicationKey = 'YOUR_API_KEY';
    private $restClient;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the REST class with the mock HTTP client
        $this->restClient = new REST($this->applicationKey);
    }

    public function testPublish()
    {
        // Define the expected request and response
        $expectedChannel = 'test-channel';
        $expectedData = ['key' => 'value'];

        // Call the publish method
        $response = $this->restClient->publish($expectedChannel, $expectedData);

        // Assert that the response status code is as expected
        $this->assertEquals(204, $response->getStatusCode());
    }
}
