<?php

namespace Sdkconsultoria\Core\Tests\Console;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Sdkconsultoria\Core\Tests\TestCase;

class MakeUserTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('sdk:permissions');
    }

    public function test_make_default()
    {
        $this->artisan('sdk:user')->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@sdkconsultoria.com',
        ]);

        $user = \Sdkconsultoria\Core\Models\User::where('email', 'admin@sdkconsultoria.com')->first();
        $this->assertTrue($user->hasRole('super-admin'));
    }

    public function test_make_custom_user()
    {
        $email = $this->faker->email;
        $this->artisan('sdk:user', ['email' => $email])->assertSuccessful();
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function test_make_defaul_exist()
    {
        $email = $this->faker->email;

        $this->artisan('sdk:user', ['email' => $email])->assertSuccessful();
        $this->artisan('sdk:user', ['email' => $email])->expectsOutput("El usuario $email ya existe")->assertSuccessful();
    }
}
