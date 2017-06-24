<?php

namespace LaravelRestcord\Http;

use GuzzleHttp\Client;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\HandlesDiscordWebhooksBeingCreated;

class WebhookCallback
{
    public function createWebhook(
        Request $request,
        Application $application,
        Repository $config,
        Client $client,
        UrlGenerator $urlGenerator
    ) : Response {
        $response = $client->post('https://discordapp.com/api/oauth2/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => env('DISCORD_KEY'),
                'client_secret' => env('DISCORD_SECRET'),
                'code'          => $request->get('code'),

                // this endpoint is never hit, it just needs to be here for OAuth compatibility
                'redirect_uri' => $urlGenerator->to(Discord::callbackUrl().'/create-webhook'),
            ],
        ]);

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $webhook = new Webhook($json['webhook']);

        /** @var HandlesDiscordWebhooksBeingCreated $webhookHandler */
        $webhookHandler = $application->make($config->get('laravel-restcord.webhook-created-handler'));

        return $webhookHandler->webhookCreated($webhook);
    }
}
