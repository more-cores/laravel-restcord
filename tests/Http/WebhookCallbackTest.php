<?php

namespace LaravelRestcord;

use Guzzle\Stream\StreamInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelRestcord\Discord\HandlesDiscordWebhooksBeingCreated;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Http\WebhookCallback;
use Mockery;
use PHPUnit\Framework\TestCase;

class WebhookCallbackTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $request;

    /** @var Mockery\MockInterface */
    protected $application;

    /** @var Mockery\MockInterface */
    protected $client;

    /** @var Mockery\MockInterface */
    protected $config;

    /** @var Mockery\MockInterface */
    protected $webhookCreatedHandler;

    /** @var Mockery\MockInterface */
    protected $urlGenerator;

    /** @var WebhookCallback */
    protected $webhookCallback;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->application = Mockery::mock(Application::class);
        $this->config = Mockery::mock(Repository::class);
        $this->client = Mockery::mock(Client::class);
        $this->webhookCreatedHandler = Mockery::mock(HandlesDiscordWebhooksBeingCreated::class);
        $this->urlGenerator = Mockery::mock(UrlGenerator::class);

        $this->webhookCallback = new WebhookCallback();
    }

    /** @test */
    public function createsWebhook()
    {
        $webhookData = [
            'id' => time(),
        ];
        $this->urlGenerator->shouldReceive('to')->with('/discord/create-webhook')->andReturn($url = uniqid());
        $this->request->shouldReceive('get')->with('code')->andReturn($code = uniqid());

        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn(\GuzzleHttp\json_encode([
            'webhook' => $webhookData,
        ]));
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->andReturn($stream);

        $this->client->shouldReceive('post')->with('https://discordapp.com/api/oauth2/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => env('DISCORD_KEY'),
                'client_secret' => env('DISCORD_SECRET'),
                'code'          => $code,
                'redirect_uri'  => $url,
            ],
        ])->andReturn($response);

        $webhookCreatedHandler = uniqid();
        $this->config->shouldReceive('get')->with('laravel-restcord.webhook-created-handler')->andReturn($webhookCreatedHandler);
        $controllerResponse = Mockery::mock(RedirectResponse::class);
        $handlesWebhookCreated = Mockery::mock(HandlesDiscordWebhooksBeingCreated::class);
        $handlesWebhookCreated->shouldReceive('webhookCreated')->with(Mockery::on(function ($arg) {
            return Webhook::class == get_class($arg);
        }))->andReturn($controllerResponse);
        $this->application->shouldReceive('make')->with($webhookCreatedHandler)->andReturn($handlesWebhookCreated);

        $this->assertEquals($controllerResponse, $this->webhookCallback->createWebhook(
            $this->request,
            $this->application,
            $this->config,
            $this->client,
            $this->urlGenerator
        ));
    }
}
