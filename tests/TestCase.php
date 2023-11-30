<?php

namespace Sdkconsultoria\Core\Tests;

use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Sdkconsultoria\Core\Providers\AuthServiceProvider;
use Sdkconsultoria\Core\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    protected static $migration;

    protected static $customMigration;

    public function setUp(): void
    {
        parent::setUp();

        if (! self::$migration) {
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
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('logging.default', 'single');
        $app['config']->set('logging.channels.single', [
            'driver' => 'single',
            // 'tap' => [App\Logging\CustomizeFormatter::class],
            'path' => __DIR__.'/logs/test.log',
            'level' => 'debug',
        ]);

        $app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', 'Sdkconsultoria\Core\Exceptions\Handler');
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
            AuthServiceProvider::class,
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
