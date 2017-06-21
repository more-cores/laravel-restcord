<?php

namespace LaravelRestcord;

use Guzzle\Stream\StreamInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\WebhookCreated;
use LaravelRestcord\Http\Middleware\InstantiateApiClientWithTokenFromSession;
use LaravelRestcord\Http\WebhookCallback;
use Mockery;
use PHPUnit\Framework\TestCase;

class InstantiateApiClientWithTokenFromSessionTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $session;

    /** @var Mockery\MockInterface */
    protected $application;

    /** @var InstantiateApiClientWithTokenFromSession */
    protected $middleware;

    public function setUp()
    {
        parent::setUp();

        $this->session = Mockery::mock(Session::class);
        $this->application = Mockery::mock(Application::class);

        $this->middleware = new InstantiateApiClientWithTokenFromSession($this->session, $this->application);
    }

    /** @test */
    public function setsClientWithTokenForDiscordWhenPresentInSession()
    {
        $client = Mockery::mock(ApiClient::class);

        $this->application->shouldReceive('make')->with(ApiClient::class)->andReturn($client);

        $this->session->shouldReceive('has')->with('discord_token')->andReturn(true);

        $this->assertEquals(1, $this->middleware->handle('', function () {
            return 1;
        }));

        $this->assertEquals($client, Discord::client());
    }

    /** @test */
    public function doesntSetClientWhenNotPresentInDiscord()
    {
        $client = Mockery::mock(ApiClient::class);

        $this->application->shouldReceive('make')->with(ApiClient::class)->andReturn($client)->never();

        $this->session->shouldReceive('has')->with('discord_token')->andReturn(false);

        $this->assertEquals(1, $this->middleware->handle('', function () {
            return 1;
        }));
    }
}
