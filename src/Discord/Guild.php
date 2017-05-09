<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;

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
        return 'https://cdn.discordapp.com/icons/' . $this->id() . '/' . $this->icon . '.jpg';
    }
}
