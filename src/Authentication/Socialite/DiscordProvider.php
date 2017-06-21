<?php

namespace LaravelRestcord\Authentication\Socialite;

use SocialiteProviders\Discord\Provider;

/**
 * @codecoverageignore
 */
class DiscordProvider extends Provider
{
    public static $token = null;

    /**
     * Tap into user so we can record the token statically.  This
     * enables us to add this to the session (via a login event)
     * and use it for future Discord API requests.
     */
    public function user()
    {
        $user = parent::user();

        self::$token = $user->token;

        return $user;
    }
}
