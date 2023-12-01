<?php

namespace Sdkconsultoria\Core\Tests\Models;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Sdkconsultoria\Core\Models\Role;
use Sdkconsultoria\Core\Tests\TestCase;

class ModelHandlerTest extends TestCase
{
    use WithFaker;

    public function test_create_fields()
    {
        $rol = Role::class;

        dd($rol::fieldsX());
    }
}
