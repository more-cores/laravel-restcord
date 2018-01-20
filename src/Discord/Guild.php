<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\Permissions\CanHavePermissions;
use LaravelRestcord\Discord\Permissions\ChecksPermissions;
use LaravelRestcord\Discord\Permissions\HasPermissions;

/**
 * This guild object represents both a partial guild object
 * and a full guild object depending on context.
 *
 * @see https://discordapp.com/developers/docs/resources/user#get-current-user-guilds
 * @see https://discordapp.com/developers/docs/resources/guild#guild-object
 */
class Guild extends Fluent implements CanHavePermissions
{
    use HasPermissions;
    use ChecksPermissions;

    /** @var ApiClient */
    protected $api;

    protected $abbreviationCache = '';

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

    public function abbreviation() : string
    {
        // because this is generated on the fly we'll cache it
        if ($this->abbreviationCache == null) {
            $firstLetters = '';
            foreach (explode(' ', trim($this->name)) as $word) {
                $firstLetters .= $word[0];
            }
            $this->abbreviationCache = strtoupper($firstLetters);
        }

        return $this->abbreviationCache;
    }

    public function hasIcon() : bool
    {
        return $this->icon != null;
    }

    public function icon() : string
    {
        return $this->icon;
    }

    public function permissions() : int
    {
        return $this->permissions;
    }

    /**
     * If guild was obtained via an oauth user we have
     * its permissions.
     */
    public function userCan(int $permission) : bool
    {
        return $this->can($this, $permission);
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
