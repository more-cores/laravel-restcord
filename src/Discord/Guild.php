<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;

class Guild extends Fluent
{
    public function id() : int
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function hasIcon() : bool
    {
        return $this->icon != null;
    }

    public function icon() : string
    {
        return $this->icon;
    }

    public function iconUrl() : string
    {
        return 'https://cdn.discordapp.com/icons/'.$this->id().'/'.$this->icon().'.jpg';
    }

    /**
     * @codecoverageignore
     */
    public function sendUserToDiscordToAddBot(int $permissions)
    {
        header('Location: '.ApiClient::API_URL.'/oauth2/authorize?client_id='.Discord::key().'&scope=bot&permissions='.$permissions.'&redirect_uri='.urlencode(Discord::callbackUrl().'/bot-added').'&response_type=code');
        exit;
    }

    /**
     * @codecoverageignore
     */
    public function sendUserToDiscordToCreateWebhook()
    {
        header('Location: '.ApiClient::API_URL.'/oauth2/authorize?client_id='.Discord::key().'&scope=webhook.incoming&redirect_uri='.urlencode(Discord::callbackUrl().'/create-webhook').'&response_type=code');
        exit;
    }
}
