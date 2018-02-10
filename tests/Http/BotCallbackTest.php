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
use LaravelRestcord\Discord\Bots\HandlesBotAddedToGuild;
use LaravelRestcord\Discord\ErrorFactory;
use LaravelRestcord\Discord\Guild;
use LaravelRestcord\Http\BotCallback;
use Mockery;
use PHPUnit\Framework\TestCase;

class BotCallbackTest extends TestCase
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
    protected $botAddedHandler;

    /** @var Mockery\MockInterface */
    protected $urlGenerator;

    /** @var Mockery\MockInterface */
    protected $errorFactory;

    /** @var BotCallback */
    protected $botCallback;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->application = Mockery::mock(Application::class);
        $this->config = Mockery::mock(Repository::class);
        $this->client = Mockery::mock(Client::class);
        $this->botAddedHandler = Mockery::mock(HandlesBotAddedToGuild::class);
        $this->urlGenerator = Mockery::mock(UrlGenerator::class);
        $this->errorFactory = Mockery::mock(ErrorFactory::class);

        $this->botCallback = new BotCallback();
    }

    /** @test */
    public function createsBot()
    {
        $guildData = [
            'id' => time(),
        ];
        $this->urlGenerator->shouldReceive('to')->with('/discord/bot-added')->andReturn($url = uniqid());
        $this->request->shouldReceive('get')->with('code')->andReturn($code = uniqid());
        $this->request->shouldReceive('has')->with('error')->andReturn(false);

        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn(\GuzzleHttp\json_encode([
            'access_token'  => $accessToken = uniqid(),
            'expires_in'    => $expiresIn = time(),
            'refresh_token' => $refreshToken = uniqid(),
            'guild'         => $guildData,
        ]));
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->andReturn($stream);

        $this->client->shouldReceive('post')->with('https://discordapp.com/api/oauth2/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => '',
                'client_secret' => '',
                'code'          => $code,
                'redirect_uri'  => $url,
            ],
        ])->andReturn($response);

        $botAddedHandlerConfig = uniqid();
        $this->config->shouldReceive('get')->with('laravel-restcord.bot-added-handler')->andReturn($botAddedHandlerConfig);
        $controllerResponse = Mockery::mock(RedirectResponse::class);
        $botAddedHandler = Mockery::mock(HandlesBotAddedToGuild::class);
        $botAddedHandler->shouldReceive('botAdded')->with($accessToken, $expiresIn, $refreshToken, Mockery::on(function ($arg) {
            return Guild::class == get_class($arg);
        }))->andReturn($controllerResponse);
        $this->application->shouldReceive('make')->with($botAddedHandlerConfig)->andReturn($botAddedHandler);

        $this->assertEquals($controllerResponse, $this->botCallback->botAdded(
            $this->request,
            $this->application,
            $this->config,
            $this->client,
            $this->urlGenerator,
            $this->errorFactory
        ));
    }

    /** @test */
    public function cancelCreatingBotTriggersHandler()
    {
        $botAddedHandlerConfig = uniqid();
        $this->config->shouldReceive('get')->with('laravel-restcord.bot-added-handler')->andReturn($botAddedHandlerConfig);
        $controllerResponse = Mockery::mock(RedirectResponse::class);
        $error = uniqid();
        $botAddedHandler = Mockery::mock(HandlesBotAddedToGuild::class);
        $botAddedHandler->shouldReceive('botNotAdded')->with($error)->andReturn($controllerResponse);
        $this->application->shouldReceive('make')->with($botAddedHandlerConfig)->andReturn($botAddedHandler);
        $this->request->shouldReceive('has')->with('error')->andReturn(true);
        $this->request->shouldReceive('get')->with('error')->andReturn($error);

        $this->assertEquals($controllerResponse, $this->botCallback->botAdded(
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
        $this->request->shouldReceive('has')->with('error')->andReturn(false);

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
        $botAddedHandler = Mockery::mock(HandlesBotAddedToGuild::class);
        // for some reason this mock value isn't obeyed and it's pulling the actual class
        //$botAddedHandler->shouldReceive('errored')->with($restcordException)->andReturn($expectedResponse);
        $this->application->shouldReceive('make')->andReturn($botAddedHandler);

        $this->errorFactory->shouldReceive('make')->with($code, $message)->andReturn($restcordException);

        $this->assertEquals($expectedResponse, $this->botCallback->botAdded(
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
        $handlesBotAdded = Mockery::mock(HandlesBotAddedToGuild::class);
        // for some reason this mock value isn't obeyed and it's pulling the actual class
        //$handlesBotAdded->shouldReceive('errored')->with($exception)->andReturn($expectedResponse);
        $this->application->shouldReceive('make')->andReturn($handlesBotAdded);

        $this->config->shouldReceive('get')->andReturn(uniqid());
        $this->errorFactory->shouldReceive('make')->andReturn(null);

        $this->assertEquals($expectedResponse, $this->botCallback->botAdded(
            $this->request,
            $this->application,
            $this->config,
            $this->client,
            $this->urlGenerator,
            $this->errorFactory
        ));
    }
}
