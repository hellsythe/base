<?php

namespace Sdkconsultoria\Core\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sdkconsultoria\Core\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;

abstract class TestCase extends Orchestra
{
    protected static $migration;

    protected static $customMigration;

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$migration) {
            $this->loadLaravelMigrations();
            $this->artisan('migrate')->run();

            self::$customMigration = true;
        }
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('auth.providers.users.model', '\Sdkconsultoria\Core\Models\User');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            PermissionServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    /**
     * Ignore package discovery from.
     *
     * @return array
     */
    public function ignorePackageDiscoveriesFrom()
    {
        return [];
    }
}
