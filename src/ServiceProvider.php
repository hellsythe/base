<?php

namespace Sdkconsultoria\Core;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerTranslations();
        $this->registerRoutes();
    }

    private function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Sdkconsultoria\Core\Console\Commands\MakePermissions::class,
                \Sdkconsultoria\Core\Console\Commands\MakeUser::class,
                \Sdkconsultoria\Core\Console\Commands\MakeApi::class,
                \Sdkconsultoria\Core\Console\Commands\InstallCommand::class,
            ]);
        }
    }

    private function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    private function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'core');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutesMacro();
        $this->registerMigrationsMacro();
    }

    private function registerMigrationsMacro()
    {
        Blueprint::macro('commonFields', function () {
            $this->id();
            $this->genericFields();
        });

        Blueprint::macro('genericFields', function () {
            $this->statusField();
            $this->timestampsFields();
            $this->creatingFields();
        });

        Blueprint::macro('creatingFields', function () {
            $this->foreignId('created_by')->nullable()->constrained('users');
            $this->foreignId('updated_by')->nullable()->constrained('users');
            $this->foreignId('deleted_by')->nullable()->constrained('users');
        });

        Blueprint::macro('timestampsFields', function () {
            $this->timestamps();
            $this->timestamp('deleted_at')->nullable();
        });

        Blueprint::macro('statusField', function () {
            $this->smallInteger('status')->default('20');
        });

        Blueprint::macro('translatable', function () {
            $table = str_replace('_translates', '', $this->table);
            $table = Str::plural($table);
            $this->unsignedBigInteger('translatable_id');
            $this->foreign('translatable_id')->references('id')->on($table);
        });
    }

    private function registerRoutesMacro()
    {
        Route::macro('SdkResource', function ($uri, $controller) {
            Route::SdkApiResourceModel("$uri/api", $controller);
            Route::SdkSimpleResource("$uri", $controller);
        });

        Route::macro('SdkApiResourceModel', function ($uri, $controller) {
            $name = str_replace('/api', '', $uri);
            Route::SdkApi($uri, $controller, $name);
        });

        Route::macro('SdkApi', function ($uri, $controller, $name = null) {
            $name = $name ?? $uri;

            Route::get("{$uri}", "{$controller}@viewAny")->name("api.{$name}.index");
            Route::get("{$uri}/{id}", "{$controller}@view")->name("api.{$name}.view");
            Route::post("{$uri}", "{$controller}@storage")->name("api.{$name}.create");
            Route::put("{$uri}/{id}", "{$controller}@update")->name("api.{$name}.update");
            Route::delete("{$uri}/{id}", "{$controller}@delete")->name("api.{$name}.delete");
        });

        Route::macro('SdkSimpleResource', function ($uri, $controller) {
            Route::get("{$uri}", "{$controller}@index")->name("{$uri}.index");
            Route::get("{$uri}/create", "{$controller}@create")->name("{$uri}.create");
            Route::get("{$uri}/update/{id}", "{$controller}@edit")->name("{$uri}.update");
            Route::get("{$uri}/{id}", "{$controller}@show")->name("{$uri}.view");
        });
    }

    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
