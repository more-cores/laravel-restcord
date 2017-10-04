<?php

namespace LaravelRestcord;

use Carbon\Carbon;
use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Member;
use LaravelRestcord\Discord\Role;
use Mockery;
use PHPUnit\Framework\TestCase;

class MemberTest extends TestCase
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
        $member = new Member([
            'joined_at'     => $name = Carbon::now()->toIso8601String(),
            'deaf'          => false,
            'mute'          => false,
        ], $this->api);

        $this->assertNotNull($member->joinedAt());
        $this->assertFalse($member->isDeaf());
        $this->assertFalse($member->isMute());
    }

    /** @test */
    public function identifiesDeafMembers()
    {
        $member = new Member([
            'deaf' => 1,
        ], $this->api);

        $this->assertTrue($member->isDeaf());
    }

    /** @test */
    public function identifiesMuteMembers()
    {
        $member = new Member([
            'mute' => 1,
        ], $this->api);

        $this->assertTrue($member->isMute());
    }

    /** @test */
    public function providesRoles()
    {
        $member = new Member([
            'roles' => [
                $role = [
                    'id' => $roleId = time()
                ]
            ]
        ], $this->api);

        $roles = $member->roles();

        $this->assertInstanceOf(Role::class, $roles[0]);
        $this->assertEquals($roleId, $roles[0]->id());
    }
}
