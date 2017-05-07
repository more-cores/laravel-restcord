<?php

namespace LaravelRestcord\Authentication\Socialite;

class DiscordExtendSocialite extends \SocialiteProviders\Discord\DiscordExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(\SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('discord', DiscordProvider::class);
    }
}
