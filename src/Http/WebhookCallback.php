<?php

namespace LaravelRestcord\Http;

use LaravelRestcord\Discord;
use GuzzleHttp\Client;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\WebhookCreated;

class WebhookCallback
{
    public function createWebhook(
        Request $request,
        Dispatcher $dispatcher,
        Client $client,
        WebhookCreated $webhookCreated,
        UrlGenerator $urlGenerator
    ) {
        $response = $client->post('https://discordapp.com/api/oauth2/token', [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('DISCORD_KEY'),
                'client_secret' => env('DISCORD_SECRET'),
                'code' => $request->get('code'),

                # this endpoint is never hit, it just needs to be here for OAuth compatibility
                'redirect_uri' => $urlGenerator->to(Discord::callbackUrl().'/create-webhook')
            ],
        ]);

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $webhookCreated->setWebhook(new Webhook($json['webhook']));

        $dispatcher->dispatch($webhookCreated);
    }
}