<?php

namespace LaravelRestcord\Discord;

use GuzzleHttp\Client;

class ApiClient
{
    const API_URL = 'https://discordapp.com/api';

    /** @var Client */
    protected $client;

    public function __construct(string $token)
    {
        $this->client = new Client(
            [
                'headers'     => [
                    'Authorization' => 'Bearer '.$token,
                    'User-Agent'    => 'LaravelRestcord (https://github.com/more-cores/laravel-restcord)',
                    'Content-Type'  => 'application/json',
                ],
            ]
        );
    }

    /**
     * Allows client to be overridden for testing purposes.
     */
    public function setGuzzleClient(Client $client)
    {
        $this->client = $client;
    }

    public function guzzleClient() : Client
    {
        return $this->client;
    }

    public function get(string $uri, array $options = []) : array
    {
        $response = $this->client->get(self::API_URL.$uri, $options);
        $responseBody = $response->getBody()->getContents();

        return \GuzzleHttp\json_decode($responseBody, true);
    }

    public function post(string $uri, array $options = []) : array
    {
        $response = $this->client->post(self::API_URL.$uri, $options);
        $responseBody = $response->getBody()->getContents();

        return \GuzzleHttp\json_decode($responseBody, true);
    }
}
