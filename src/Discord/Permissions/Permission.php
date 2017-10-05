<?php

namespace LaravelRestcord\Discord\Permissions;

interface Permission
{
    public const CREATE_INSTANT_INVITE = 0x00000001;
    public const KICK_MEMBERS = 0x00000002;
    public const BAN_MEMBERS = 0x00000004;
    public const ADMINISTRATOR = 0x00000008;
    public const MANAGE_CHANNELS = 0x00000010;
    public const MANAGE_GUILD = 0x00000020;
    public const ADD_REACTIONS = 0x00000040;
    public const VIEW_AUDIT_LOG = 0x00000080;
    public const READ_MESSAGES = 0x00000400;
    public const SEND_MESSAGES = 0x00000800;
    public const SEND_TTS_MESSAGES = 0x00001000;
    public const MANAGE_MESSAGES = 0x00002000;
    public const EMBED_LINKS = 0x00004000;
    public const ATTACH_FILES = 0x00008000;
    public const READ_MESSAGE_HISTORY = 0x00010000;
    public const MENTION_EVERYONE = 0x00020000;
    public const USE_EXTERNAL_EMOJIS = 0x00040000;
    public const CONNECT = 0x00100000;
    public const SPEAK = 0x00200000;
    public const MUTE_MEMBERS = 0x00400000;
    public const DEAFEN_MEMBERS = 0x00800000;
    public const MOVE_MEMBERS = 0x01000000;
    public const USE_VAD = 0x02000000;
    public const CHANGE_NICKNAME = 0x04000000;
    public const MANAGE_NICKNAMES = 0x08000000;
    public const MANAGE_ROLES = 0x10000000;
    public const MANAGE_WEBHOOKS = 0x20000000;
    public const MANAGE_EMOJIS = 0x40000000;
}
