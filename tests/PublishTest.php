<?php

use PHPUnit\Framework\TestCase;
use Pubq\REST;

class PublishTest extends TestCase
{
    private $rest;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an instance of the REST class with the mock HTTP client
        // Replace the value with your actual API key
        $this->rest = new REST(['key' => 'YOUR_API_KEY']);
    }

    public function testPublishResponse()
    {
        // Define the expected request and response
        $expectedChannel = 'test-channel';
        $expectedData = ['key' => 'value'];

        // Call the publish method
        $channel = $this->rest->channels->get($expectedChannel);
        $response = $channel->publish($expectedData);

        // Assert that the response status code is as expected
        $this->assertEmpty($response);
    }
}
