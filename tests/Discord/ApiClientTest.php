<?php

namespace LaravelRestcord;

use GuzzleHttp\Client;
use LaravelRestcord\Discord\ApiClient;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ApiClientTest extends TestCase
{
    /** @var ApiClient */
    protected $apiClient;

    /** @var string */
    protected $token;

    public function setUp()
    {
        parent::setUp();

        $this->token = uniqid();
        $this->apiClient = new ApiClient($this->token);
    }

    /** @test */
    public function providesConfiguredGuzzleClient()
    {
        $config = $this->apiClient->guzzleClient()->getConfig();

        $this->assertEquals('Bearer '.$this->token, $config['headers']['Authorization']);
        $this->assertEquals('LaravelRestcord (https://github.com/more-cores/laravel-restcord)', $config['headers']['User-Agent']);
        $this->assertEquals('application/json', $config['headers']['Content-Type']);
    }

    /** @test */
    public function decodesJsonGetRequests()
    {
        $options = [];
        $uri = uniqid();
        $requestData = [
            'key' => 'value',
        ];

        // Following the PSR standards for requests leads to a lot of shenanigans
        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn(\GuzzleHttp\json_encode($requestData));
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn($stream);

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('get')->with($uri, $options)->andReturn($response);

        $this->apiClient->setGuzzleClient($client);

        $this->assertEquals($requestData, $this->apiClient->get($uri, $options));
    }
}
