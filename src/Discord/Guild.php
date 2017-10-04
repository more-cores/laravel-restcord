<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;

class Guild extends Fluent
{
    /** @var ApiClient */
    protected $api;

    public function __construct(array $attributes = [], ?ApiClient $apiClient = null)
    {
        parent::__construct($attributes);

        if ($apiClient == null) {
            $apiClient = Discord::client();
        }
        $this->api = $apiClient;
    }

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

    public function getMemberById(int $userId) : Member
    {
        $memberData = $this->api->get('/guilds/'.$this->id.'/members/'.$userId);

        return new Member($memberData, $this->api);
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
