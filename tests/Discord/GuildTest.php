<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\Guild;
use PHPUnit\Framework\TestCase;

class GuildTest extends TestCase
{
    /** @test */
    public function getsAndSetsProperties()
    {
        $guild = new Guild([
            'id'    => $id = time(),
            'name'  => $name = uniqid(),
            'icon'  => $icon = uniqid(),
        ]);

        $this->assertEquals($id, $guild->id());
        $this->assertEquals($name, $guild->name());
        $this->assertEquals($icon, $guild->icon());
    }
}
