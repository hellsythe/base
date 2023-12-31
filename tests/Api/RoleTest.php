<?php

namespace Sdkconsultoria\Core\Tests\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Sdkconsultoria\Core\Models\Role;
use Sdkconsultoria\Core\Models\User;
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

    public function test_read_all()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:viewAny');
        Role::create(['name' => $this->faker->name]);
        Role::create(['name' => $this->faker->name]);
        Role::create(['name' => $this->faker->name]);

        $this->actingAs($user)->get('/api/v1/role')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:create');

        $this->actingAs($user)->postJson('/api/v1/role', [
            'name' => $this->faker->name,
        ])->assertStatus(201);
    }

    public function test_create_validation()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:create');

        $this->actingAs($user)->postJson('/api/v1/role', [])->assertStatus(400);
    }

    public function test_read()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:view');
        $role = Role::create(['name' => $this->faker->name]);

        $this->actingAs($user)->get("/api/v1/role/{$role->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:update');
        $role = Role::create(['name' => $this->faker->name]);
        $name = $this->faker->name;

        $this->actingAs($user)->putJson("/api/v1/role/{$role->id}", [
            'name' => $name,
        ])->assertStatus(200);

        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => $name]);
    }

    public function test_delete()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:delete');
        $role = Role::create(['name' => $this->faker->name]);
        $this->actingAs($user)->delete("/api/v1/role/{$role->id}")->assertStatus(200);
        $this->assertSoftDeleted('roles', ['id' => $role->id]);
    }

    public function test_search_by_name()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:viewAny');
        $name = $this->faker->name;
        Role::create(['name' => $this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => $this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => $this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => $name, 'status' => Role::STATUS_ACTIVE]);

        $this->actingAs($user)->get("/api/v1/role?name={$name}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_order_by_name()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('role:viewAny');
        Role::create(['name' => 'ya'.$this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => 'aa'.$this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => 'za'.$this->faker->name, 'status' => Role::STATUS_ACTIVE]);
        Role::create(['name' => 'ba'.$this->faker->name, 'status' => Role::STATUS_ACTIVE]);

        $response_asc = $this->actingAs($user)->get('/api/v1/role?order=name')->assertStatus(200);
        $response_desc = $this->actingAs($user)->get('/api/v1/role?order=-name')->assertStatus(200);

        $this->assertStringStartsWith('aa', $response_asc['data'][0]['name']);
        $this->assertStringStartsWith('za', $response_desc['data'][0]['name']);
    }
}
