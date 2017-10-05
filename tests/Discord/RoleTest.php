<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Permissions\ChecksPermissions;
use LaravelRestcord\Discord\Permissions\HasPermissions;
use LaravelRestcord\Discord\Role;
use Mockery;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = Mockery::mock(ApiClient::class);
    }

    /** @test */
    public function expectsPermissionManagement()
    {
        $role = new Role([], $this->api);
        $traitsInUse = class_uses($role);
        $this->assertContains(HasPermissions::class, $traitsInUse);
        $this->assertContains(ChecksPermissions::class, $traitsInUse);
    }

    /** @test */
    public function getsAndSetsProperties()
    {
        $role = new Role([
            'id'            => $id = time() + rand(1, 400),
            'name'          => $name = uniqid(),
            'color'         => $color = time() + rand(10, 4000),
            'permissions'   => $permissions = time() + rand(100, 40000),
        ], $this->api);

        $this->assertEquals($id, $role->id());
        $this->assertEquals($name, $role->name());
        $this->assertEquals($color, $role->color());
        $this->assertEquals($permissions, $role->permissions());
        $this->assertFalse($role->isHoisted());
    }

    /** @test */
    public function identifiesHoistedRoles()
    {
        $role = new Role([
            'hoist' => 1,
        ], $this->api);

        $this->assertTrue($role->isHoisted());
    }
}
