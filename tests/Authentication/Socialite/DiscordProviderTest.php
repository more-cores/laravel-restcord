<?php

namespace LaravelRestcord\Authentication\Socialite;

use Mockery;
use PHPUnit\Framework\TestCase;
use SocialiteProviders\Manager\SocialiteWasCalled;

class DiscordProviderTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $event;

    /** @var DiscordExtendSocialite */
    protected $socialteRegistrar;

    public function setUp()
    {
        parent::setUp();

        $this->event = Mockery::mock(SocialiteWasCalled::class);
        $this->socialteRegistrar = new DiscordProvider();
    }

    /** @test */
    public function tokenIsRegisteredWhenUserIsBuilt()
    {
        $this->socialteRegistrar->handle($this->event);
    }
}
