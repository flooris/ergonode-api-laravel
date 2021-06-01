<?php

namespace Flooris\ErgonodeApi;

use Illuminate\Support\ServiceProvider;

class ErgonodeApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ergonode.php' => config_path('ergonode.php'),
        ], 'ergonode-api-laravel');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ergonode.php', 'ergonode'
        );
    }
}
