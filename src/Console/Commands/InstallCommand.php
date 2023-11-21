<?php

namespace Sdkconsultoria\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Sdkconsultoria\Core\Service\FileManager;
use Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sdk:core-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instala la libreria SDK';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->copyStubs();
        $this->writteUserChanges();
        $this->writteConfig();
        Artisan::call('sdk:permissions');

        $this->info('SDK Core se instalo correctamente.');
    }

    /**
     * Copia los stubs.
     *
     * @return void
     */
    private function copyStubs()
    {
        (new Filesystem)->ensureDirectoryExists(app_path('stubs'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/stubs', base_path('stubs'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/routes', base_path('routes'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/app', base_path('app'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../../stubs/resources', base_path('resources'));
    }

    private function writteUserChanges()
    {
        copy(__DIR__.'/../../../stubs/models/User.php', base_path('app/Models/User.php'));
        copy(__DIR__.'/../../../stubs/factories/UserFactory.php', base_path('database/factories/UserFactory.php'));
    }

    private function writteConfig()
    {
        $file = base_path('config').'/app.php';

        FileManager::replace(
            "'locale' => 'en',",
            "'locale' => 'es',",
            $file
        );

        // FileManager::writteAfter(
        //     "App\Providers\RouteServiceProvider::class,",
        //     "\n**Sdkconsultoria\Core\Providers\AuthServiceProvider::class,",
        //     $file
        // );
    }
}
