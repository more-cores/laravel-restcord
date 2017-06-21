<?php

namespace LaravelRestcord;

use Guzzle\Stream\StreamInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\WebhookCreated;
use LaravelRestcord\Http\WebhookCallback;
use Mockery;
use PHPUnit\Framework\TestCase;

class WebhookCallbackTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $request;

    /** @var Mockery\MockInterface */
    protected $dispatcher;

    /** @var Mockery\MockInterface */
    protected $client;

    /** @var Mockery\MockInterface */
    protected $webhookCreated;

    /** @var Mockery\MockInterface */
    protected $urlGenerator;

    /** @var WebhookCallback */
    protected $webhookCallback;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->client = Mockery::mock(Client::class);
        $this->webhookCreated = Mockery::mock(WebhookCreated::class);
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

        $this->dispatcher->shouldReceive('dispatch')->with($this->webhookCreated);
        $this->webhookCreated->shouldReceive('setWebhook')->with(Mockery::on(function ($arg) {
            return Webhook::class == get_class($arg);
        }));

        $this->webhookCallback->createWebhook(
            $this->request,
            $this->dispatcher,
            $this->client,
            $this->webhookCreated,
            $this->urlGenerator
        );
    }
}
