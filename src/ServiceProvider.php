<?php

namespace Davesweb\LaravelTranslatable;

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
    }
}
