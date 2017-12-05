<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;
use LaravelRestcord\Discord\Permissions\Permission;
use Mockery;
use PHPUnit\Framework\TestCase;

class GuildTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = Mockery::mock(ApiClient::class);
    }

    /** @test */
    public function getsAndSetsProperties()
    {
        $guild = new Guild([
            'id'            => $id = time(),
            'name'          => $name = uniqid(),
            'icon'          => $icon = uniqid(),
            'permissions'   => $permissions = time(),
        ], $this->api);

        $this->assertEquals($id, $guild->id());
        $this->assertEquals($name, $guild->name());
        $this->assertEquals('https://cdn.discordapp.com/icons/'.$guild->id().'/'.$icon.'.jpg', $guild->iconUrl());
        $this->assertEquals($icon, $guild->icon());
        $this->assertEquals($permissions, $guild->permissions());
        $this->assertTrue($guild->hasIcon());
    }

    /** @test */
    public function abbreviatesName()
    {
        $guild = new Guild([
            'name'          => 'Waffle Cone',
        ], $this->api);

        $this->assertEquals('WC', $guild->abbreviation());
    }

    /** @test */
    public function getMemberByUserId()
    {
        $guildId = rand(101, 200);
        $memberId = rand(1, 100);

        $guild = new Guild([
            'id' => $guildId,
        ], $this->api);
        $memberData = [
            'roles' => [
                [
                    'name' => $name = uniqid(),
                ],
            ],
        ];

        $this->api->shouldReceive('get')->with('/guilds/'.$guildId.'/members/'.$memberId)->andReturn($memberData);

        $obtainedMember = $guild->getMemberById($memberId);

        $this->assertEquals($name, $obtainedMember->roles()[0]->name);
    }

    /** @test */
    public function recognizesWhenIconIsMissing()
    {
        $guild = new Guild([], $this->api);

        $this->assertFalse($guild->hasIcon());
    }

    /** @test */
    public function authorizesPermissions()
    {
        $guild = new Guild([
            'permissions' => Permission::SEND_MESSAGES,
        ], $this->api);

        $this->assertFalse($guild->userCan(Permission::MANAGE_GUILD));
        $this->assertTrue($guild->userCan(Permission::SEND_MESSAGES));
    }
}
