<?php

namespace LaravelRestcord\Authentication\Socialite;

use Mockery;
use PHPUnit\Framework\TestCase;
use SocialiteProviders\Manager\SocialiteWasCalled;

class DiscordExtendSocialiteTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $event;

    /** @var DiscordExtendSocialite */
    protected $socialteRegistrar;

    public function setUp()
    {
        parent::setUp();

        $this->event = Mockery::mock(SocialiteWasCalled::class);
        $this->socialteRegistrar = new DiscordExtendSocialite();
    }

    /** @test */
    public function registersDiscordSocialiteDriver()
    {
        $this->event->shouldReceive('extendSocialite')->with('discord', DiscordProvider::class)->once();

        $this->socialteRegistrar->handle($this->event);

        // assertion is performed by Mockery, this is just to avoid this test from being risky
        $this->assertTrue(true);
    }
}
