<?php

namespace LaravelRestcord\Discord\Permissions;

use PHPUnit\Framework\TestCase;

class HasAndChecksPermissionsTest extends TestCase
{
    /** @test */
    public function authorizesAllPermissions()
    {
        $permissions = new AllPermissions();
        $user = new UserStub();

        $this->assertTrue($permissions->hasPermission(Permission::ADMINISTRATOR));
        $this->assertTrue($permissions->hasPermission(Permission::CREATE_INSTANT_INVITE));
        $this->assertTrue($permissions->hasPermission(Permission::KICK_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::BAN_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_CHANNELS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_GUILD));
        $this->assertTrue($permissions->hasPermission(Permission::ADD_REACTIONS));
        $this->assertTrue($permissions->hasPermission(Permission::VIEW_AUDIT_LOG));
        $this->assertTrue($permissions->hasPermission(Permission::READ_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::SEND_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::SEND_TTS_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::EMBED_LINKS));
        $this->assertTrue($permissions->hasPermission(Permission::ATTACH_FILES));
        $this->assertTrue($permissions->hasPermission(Permission::READ_MESSAGE_HISTORY));
        $this->assertTrue($permissions->hasPermission(Permission::MENTION_EVERYONE));
        $this->assertTrue($permissions->hasPermission(Permission::USE_EXTERNAL_EMOJIS));
        $this->assertTrue($permissions->hasPermission(Permission::CONNECT));
        $this->assertTrue($permissions->hasPermission(Permission::SPEAK));
        $this->assertTrue($permissions->hasPermission(Permission::MUTE_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::DEAFEN_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::MOVE_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::USE_VAD));
        $this->assertTrue($permissions->hasPermission(Permission::CHANGE_NICKNAME));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_NICKNAMES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_ROLES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_WEBHOOKS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_EMOJIS));

        $this->assertTrue($user->can($permissions, Permission::ADMINISTRATOR));
        $this->assertTrue($user->can($permissions, Permission::CREATE_INSTANT_INVITE));
        $this->assertTrue($user->can($permissions, Permission::KICK_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::BAN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_CHANNELS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_GUILD));
        $this->assertTrue($user->can($permissions, Permission::ADD_REACTIONS));
        $this->assertTrue($user->can($permissions, Permission::VIEW_AUDIT_LOG));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_TTS_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::EMBED_LINKS));
        $this->assertTrue($user->can($permissions, Permission::ATTACH_FILES));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGE_HISTORY));
        $this->assertTrue($user->can($permissions, Permission::MENTION_EVERYONE));
        $this->assertTrue($user->can($permissions, Permission::USE_EXTERNAL_EMOJIS));
        $this->assertTrue($user->can($permissions, Permission::CONNECT));
        $this->assertTrue($user->can($permissions, Permission::SPEAK));
        $this->assertTrue($user->can($permissions, Permission::MUTE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::DEAFEN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MOVE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::USE_VAD));
        $this->assertTrue($user->can($permissions, Permission::CHANGE_NICKNAME));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_NICKNAMES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_ROLES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_WEBHOOKS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_EMOJIS));
    }

    /** @test */
    public function authorizesAllPermissionsWithOnlyAdmin()
    {
        $permissions = new OnlyAdminPermissions();
        $user = new UserStub();

        $this->assertTrue($permissions->hasPermission(Permission::ADMINISTRATOR));
        $this->assertFalse($permissions->hasPermission(Permission::CREATE_INSTANT_INVITE));
        $this->assertFalse($permissions->hasPermission(Permission::KICK_MEMBERS));
        $this->assertFalse($permissions->hasPermission(Permission::BAN_MEMBERS));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_CHANNELS));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_GUILD));
        $this->assertFalse($permissions->hasPermission(Permission::ADD_REACTIONS));
        $this->assertFalse($permissions->hasPermission(Permission::VIEW_AUDIT_LOG));
        $this->assertFalse($permissions->hasPermission(Permission::READ_MESSAGES));
        $this->assertFalse($permissions->hasPermission(Permission::SEND_MESSAGES));
        $this->assertFalse($permissions->hasPermission(Permission::SEND_TTS_MESSAGES));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_MESSAGES));
        $this->assertFalse($permissions->hasPermission(Permission::EMBED_LINKS));
        $this->assertFalse($permissions->hasPermission(Permission::ATTACH_FILES));
        $this->assertFalse($permissions->hasPermission(Permission::READ_MESSAGE_HISTORY));
        $this->assertFalse($permissions->hasPermission(Permission::MENTION_EVERYONE));
        $this->assertFalse($permissions->hasPermission(Permission::USE_EXTERNAL_EMOJIS));
        $this->assertFalse($permissions->hasPermission(Permission::CONNECT));
        $this->assertFalse($permissions->hasPermission(Permission::SPEAK));
        $this->assertFalse($permissions->hasPermission(Permission::MUTE_MEMBERS));
        $this->assertFalse($permissions->hasPermission(Permission::DEAFEN_MEMBERS));
        $this->assertFalse($permissions->hasPermission(Permission::MOVE_MEMBERS));
        $this->assertFalse($permissions->hasPermission(Permission::USE_VAD));
        $this->assertFalse($permissions->hasPermission(Permission::CHANGE_NICKNAME));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_NICKNAMES));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_ROLES));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_WEBHOOKS));
        $this->assertFalse($permissions->hasPermission(Permission::MANAGE_EMOJIS));

        $this->assertTrue($user->can($permissions, Permission::ADMINISTRATOR));
        $this->assertTrue($user->can($permissions, Permission::CREATE_INSTANT_INVITE));
        $this->assertTrue($user->can($permissions, Permission::KICK_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::BAN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_CHANNELS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_GUILD));
        $this->assertTrue($user->can($permissions, Permission::ADD_REACTIONS));
        $this->assertTrue($user->can($permissions, Permission::VIEW_AUDIT_LOG));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_TTS_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::EMBED_LINKS));
        $this->assertTrue($user->can($permissions, Permission::ATTACH_FILES));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGE_HISTORY));
        $this->assertTrue($user->can($permissions, Permission::MENTION_EVERYONE));
        $this->assertTrue($user->can($permissions, Permission::USE_EXTERNAL_EMOJIS));
        $this->assertTrue($user->can($permissions, Permission::CONNECT));
        $this->assertTrue($user->can($permissions, Permission::SPEAK));
        $this->assertTrue($user->can($permissions, Permission::MUTE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::DEAFEN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MOVE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::USE_VAD));
        $this->assertTrue($user->can($permissions, Permission::CHANGE_NICKNAME));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_NICKNAMES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_ROLES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_WEBHOOKS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_EMOJIS));
    }

    /** @test */
    public function authorizesAllPermissionsWithoutAdmin()
    {
        $permissions = new AllPermissionsWithoutAdmin();
        $user = new UserStub();

        $this->assertFalse($permissions->hasPermission(Permission::ADMINISTRATOR));
        $this->assertTrue($permissions->hasPermission(Permission::CREATE_INSTANT_INVITE));
        $this->assertTrue($permissions->hasPermission(Permission::KICK_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::BAN_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_CHANNELS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_GUILD));
        $this->assertTrue($permissions->hasPermission(Permission::ADD_REACTIONS));
        $this->assertTrue($permissions->hasPermission(Permission::VIEW_AUDIT_LOG));
        $this->assertTrue($permissions->hasPermission(Permission::READ_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::SEND_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::SEND_TTS_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_MESSAGES));
        $this->assertTrue($permissions->hasPermission(Permission::EMBED_LINKS));
        $this->assertTrue($permissions->hasPermission(Permission::ATTACH_FILES));
        $this->assertTrue($permissions->hasPermission(Permission::READ_MESSAGE_HISTORY));
        $this->assertTrue($permissions->hasPermission(Permission::MENTION_EVERYONE));
        $this->assertTrue($permissions->hasPermission(Permission::USE_EXTERNAL_EMOJIS));
        $this->assertTrue($permissions->hasPermission(Permission::CONNECT));
        $this->assertTrue($permissions->hasPermission(Permission::SPEAK));
        $this->assertTrue($permissions->hasPermission(Permission::MUTE_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::DEAFEN_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::MOVE_MEMBERS));
        $this->assertTrue($permissions->hasPermission(Permission::USE_VAD));
        $this->assertTrue($permissions->hasPermission(Permission::CHANGE_NICKNAME));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_NICKNAMES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_ROLES));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_WEBHOOKS));
        $this->assertTrue($permissions->hasPermission(Permission::MANAGE_EMOJIS));

        $this->assertFalse($user->can($permissions, Permission::ADMINISTRATOR));
        $this->assertTrue($user->can($permissions, Permission::CREATE_INSTANT_INVITE));
        $this->assertTrue($user->can($permissions, Permission::KICK_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::BAN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_CHANNELS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_GUILD));
        $this->assertTrue($user->can($permissions, Permission::ADD_REACTIONS));
        $this->assertTrue($user->can($permissions, Permission::VIEW_AUDIT_LOG));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::SEND_TTS_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_MESSAGES));
        $this->assertTrue($user->can($permissions, Permission::EMBED_LINKS));
        $this->assertTrue($user->can($permissions, Permission::ATTACH_FILES));
        $this->assertTrue($user->can($permissions, Permission::READ_MESSAGE_HISTORY));
        $this->assertTrue($user->can($permissions, Permission::MENTION_EVERYONE));
        $this->assertTrue($user->can($permissions, Permission::USE_EXTERNAL_EMOJIS));
        $this->assertTrue($user->can($permissions, Permission::CONNECT));
        $this->assertTrue($user->can($permissions, Permission::SPEAK));
        $this->assertTrue($user->can($permissions, Permission::MUTE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::DEAFEN_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::MOVE_MEMBERS));
        $this->assertTrue($user->can($permissions, Permission::USE_VAD));
        $this->assertTrue($user->can($permissions, Permission::CHANGE_NICKNAME));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_NICKNAMES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_ROLES));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_WEBHOOKS));
        $this->assertTrue($user->can($permissions, Permission::MANAGE_EMOJIS));
    }
}

class UserStub
{
    use ChecksPermissions;
}

class AllPermissions implements CanHavePermissions
{
    use HasPermissions;

    public function permissions(): int
    {
        // https://discordapi.com/permissions.html#2146958591
        return 2146958591;
    }
}

class AllPermissionsWithoutAdmin implements CanHavePermissions
{
    use HasPermissions;

    public function permissions(): int
    {
        // https://discordapi.com/permissions.html#2146958583
        return 2146958583;
    }
}

class OnlyAdminPermissions implements CanHavePermissions
{
    use HasPermissions;

    public function permissions(): int
    {
        // https://discordapi.com/permissions.html#8
        return 8;
    }
}
