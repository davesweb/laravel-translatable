<?php

namespace Davesweb\LaravelTranslatable;

use Illuminate\Contracts\Foundation\Application;
use Davesweb\LaravelTranslatable\Services\MigrationCreator;
use Davesweb\LaravelTranslatable\Console\Commands\MakeModelCommand;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Davesweb\LaravelTranslatable\Console\Commands\MakeMigrationCommand;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMigrationCommand::class,
                MakeModelCommand::class,
            ]);
        }
    }

    public function register()
    {
        if (!$this->app->bound(MigrationCreator::class)) {
            $this->app->bind(MigrationCreator::class, function (Application $app) {
                return new MigrationCreator($app->make('files'), '');
            });
        }
    }
}
