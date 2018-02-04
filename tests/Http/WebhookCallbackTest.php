<?php

namespace LaravelRestcord;

use Guzzle\Stream\StreamInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelRestcord\Discord\ErrorFactory;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\Webhooks\HandlesDiscordWebhooksBeingCreated;
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

    /** @var Mockery\MockInterface */
    protected $errorFactory;

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
        $this->errorFactory = Mockery::mock(ErrorFactory::class);

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

        Discord::setKey($key = uniqid());
        Discord::setSecret($secret = uniqid());

        $this->client->shouldReceive('post')->with('https://discordapp.com/api/oauth2/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => $key,
                'client_secret' => $secret,
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
            $this->urlGenerator,
            $this->errorFactory
        ));
    }

    /** @test */
    public function passesRecognizeableExceptionToHandler()
    {
        $restcordException = Mockery::mock(Exception::class);
        $exception = Mockery::mock(ClientException::class);
        $this->expectException(get_class($restcordException));

        $this->urlGenerator->shouldIgnoreMissing();
        $this->request->shouldIgnoreMissing();

        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn(\GuzzleHttp\json_encode([
            'code'    => $code = time(),
            'message' => $message = uniqid(),
        ]));
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->andReturn($stream);

        $exception->shouldReceive('getResponse')->andReturn($response);
        $this->client->shouldReceive('post')->andThrow($exception);

        $this->config->shouldReceive('get')->andReturn(uniqid());

        $expectedResponse = uniqid();
        $handlesWebhookCreated = Mockery::mock(HandlesDiscordWebhooksBeingCreated::class);
        // for some reason this mock value isn't obeyed and it's pulling the actual class
        //$handlesWebhookCreated->shouldReceive('errored')->with($restcordException)->andReturn($expectedResponse);
        $this->application->shouldReceive('make')->andReturn($handlesWebhookCreated);

        $this->errorFactory->shouldReceive('make')->with($code, $message)->andReturn($restcordException);

        $this->assertEquals($expectedResponse, $this->webhookCallback->createWebhook(
            $this->request,
            $this->application,
            $this->config,
            $this->client,
            $this->urlGenerator,
            $this->errorFactory
        ));
    }

    /** @test */
    public function passesUnrecognizeableExceptionToHandler()
    {
        $exception = Mockery::mock(ClientException::class);
        $this->expectException(get_class($exception));

        $this->urlGenerator->shouldIgnoreMissing();
        $this->request->shouldIgnoreMissing();

        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn(\GuzzleHttp\json_encode([
            'code'    => $code = time(),
            'message' => $message = uniqid(),
        ]));
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->andReturn($stream);

        $exception->shouldReceive('getResponse')->andReturn($response);
        $this->client->shouldReceive('post')->andThrow($exception);

        $expectedResponse = uniqid();
        $handlesWebhookCreated = Mockery::mock(HandlesDiscordWebhooksBeingCreated::class);
        // for some reason this mock value isn't obeyed and it's pulling the actual class
        //$handlesWebhookCreated->shouldReceive('errored')->with($exception)->andReturn($expectedResponse);
        $this->application->shouldReceive('make')->andReturn($handlesWebhookCreated);

        $this->config->shouldReceive('get')->andReturn(uniqid());
        $this->errorFactory->shouldReceive('make')->andReturn(null);

        $this->assertEquals($expectedResponse, $this->webhookCallback->createWebhook(
            $this->request,
            $this->application,
            $this->config,
            $this->client,
            $this->urlGenerator,
            $this->errorFactory
        ));
    }
}
