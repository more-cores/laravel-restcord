<?php

namespace LaravelRestcord\Discord\Permissions;

use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /** @test */
    public function permissionHexValuesAreUnchanged()
    {
        $this->assertEquals(1, Permission::CREATE_INSTANT_INVITE);
        $this->assertEquals(2, Permission::KICK_MEMBERS);
        $this->assertEquals(4, Permission::BAN_MEMBERS);
        $this->assertEquals(8, Permission::ADMINISTRATOR);
        $this->assertEquals(16, Permission::MANAGE_CHANNELS);
        $this->assertEquals(32, Permission::MANAGE_GUILD);
        $this->assertEquals(64, Permission::ADD_REACTIONS);
        $this->assertEquals(128, Permission::VIEW_AUDIT_LOG);
        $this->assertEquals(1024, Permission::READ_MESSAGES);
        $this->assertEquals(2048, Permission::SEND_MESSAGES);
        $this->assertEquals(4096, Permission::SEND_TTS_MESSAGES);
        $this->assertEquals(8192, Permission::MANAGE_MESSAGES);
        $this->assertEquals(16384, Permission::EMBED_LINKS);
        $this->assertEquals(32768, Permission::ATTACH_FILES);
        $this->assertEquals(65536, Permission::READ_MESSAGE_HISTORY);
        $this->assertEquals(131072, Permission::MENTION_EVERYONE);
        $this->assertEquals(262144, Permission::USE_EXTERNAL_EMOJIS);
        $this->assertEquals(1048576, Permission::CONNECT);
        $this->assertEquals(2097152, Permission::SPEAK);
        $this->assertEquals(4194304, Permission::MUTE_MEMBERS);
        $this->assertEquals(8388608, Permission::DEAFEN_MEMBERS);
        $this->assertEquals(16777216, Permission::MOVE_MEMBERS);
        $this->assertEquals(33554432, Permission::USE_VAD);
        $this->assertEquals(67108864, Permission::CHANGE_NICKNAME);
        $this->assertEquals(134217728, Permission::MANAGE_NICKNAMES);
        $this->assertEquals(268435456, Permission::MANAGE_ROLES);
        $this->assertEquals(536870912, Permission::MANAGE_WEBHOOKS);
        $this->assertEquals(1073741824, Permission::MANAGE_EMOJIS);
    }
}
