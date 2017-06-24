<?php

namespace LaravelRestcord\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\Webhooks\HandlesDiscordWebhooksBeingCreated;
use LaravelRestcord\Discord\ErrorFactory;
use LaravelRestcord\Discord\Webhook;

class WebhookCallback
{
    public function createWebhook(
        Request $request,
        Application $application,
        Repository $config,
        Client $client,
        UrlGenerator $urlGenerator,
        ErrorFactory $errorFactory
    ) {
        /** @var HandlesDiscordWebhooksBeingCreated $webhookHandler */
        $webhookHandler = $application->make($config->get('laravel-restcord.webhook-created-handler'));

        try {
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
        } catch (ClientException $e) {
            $json = \GuzzleHttp\json_decode($e->getResponse()->getBody()->getContents(), true);

            // Provide a more developer-friendly error message for common errors
            if (isset($json['code'])) {
                $exception = $errorFactory->make($json['code'], $json['message']);

                if ($exception != null) {
                    $e = $exception;
                }
            }

            return $webhookHandler->errored($e);
        }

        $json = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        $webhook = new Webhook($json['webhook']);

        return $webhookHandler->webhookCreated($webhook);
    }
}
