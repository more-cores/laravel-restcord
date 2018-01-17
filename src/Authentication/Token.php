<?php

namespace LaravelRestcord\Authentication;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Fluent;
use LaravelRestcord\Discord\ApiClient;

class Token extends Fluent
{
    public function setToken(string $token)
    {
        $this->attributes['token'] = $token;
    }

    public function token() : string
    {
        return $this->attributes['token'];
    }

    public function setRefreshToken(string $token)
    {
        $this->attributes['refresh_token'] = $token;
    }

    protected function refreshToken() : string
    {
        return $this->attributes['refresh_token'];
    }

    public function setExpiresIn(int $expiresIn)
    {
        $dateTime = Carbon::now()->addSeconds($expiresIn);
        $this->attributes['expires_at'] = $dateTime->toIso8601String();
    }

    public function expiresAt() : Carbon
    {
        return new Carbon($this->attributes['expires_at']);
    }

    public function isExpired() : bool
    {
        return $this->expiresAt()->lessThanOrEqualTo(Carbon::now());
    }

    public function refresh(string $redirectUri) : bool
    {
        $client = new Client(
            [
                'headers'     => [
                    'Content-Type'=> 'application/x-www-form-urlencoded',
                ],
            ]
        );

        $response = $client->post(ApiClient::API_URL.'/oauth2/token', [
            'client_id'     => env('DISCORD_KEY'),
            'client_secret' => env('DISCORD_SECRET'),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $this->refreshToken(),

            // we aren't actually redirecting here, this is just another form of auth
            'redirect_uri' => $redirectUri,
        ]);

        $this->setToken($response->access_token);
        $this->setExpiresIn($response->expires_in);
        $this->setRefreshToken($response->refresh_token);

        return true;
    }
}
