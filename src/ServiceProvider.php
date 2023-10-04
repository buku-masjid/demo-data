<?php

namespace BukuMasjid\DemoData;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateDemoData::class,
                RemoveDemoData::class,
            ]);
        }
    }

    public function boot()
    {
        //
    }
}
