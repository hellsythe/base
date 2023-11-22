<?php

namespace Sdkconsultoria\Core\Tests\Api;

use Sdkconsultoria\Core\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Sdkconsultoria\Core\Tests\TestCase;

class RoleTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('sdk:permissions');
    }

    public function test_unauthenticated()
    {
        $this->postJson('/api/v1/role', [])->assertStatus(401);
    }

    public function test_unauthorized()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson('/api/v1/role', [])->assertStatus(403);
    }

    // 'permissions' => [
    //     'user' => [
    //         'create' => true,
    //         'update' => true,
    //         'delete' => true,
    //         'view' => true,
    //     ],
    //     'role' => [
    //         'create' => true,
    //         'update' => true,
    //         'delete' => true,
    //         'view' => true,
    //     ],
    // ],
}
