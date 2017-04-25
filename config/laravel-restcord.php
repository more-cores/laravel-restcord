<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bot Token
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'bot-token' => getenv('DISCORD_BOT_TOKEN'),

    // Whether or not an exception is thrown when a ratelimit is supposed to hit
    'throw-exception-on-rate-limit' => getenv('DISCORD_USE_EXCEPTIONS', true),
];
