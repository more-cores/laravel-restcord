<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;

class Guild extends Fluent
{
    public function id() : string
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function icon() : string
    {
        return $this->icon;
    }
}
