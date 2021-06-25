<?php

namespace Flooris\Ergonode;

use Illuminate\Support\ServiceProvider;

class ErgonodeApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/ergonode.php' => config_path('ergonode.php'),
        ], 'ergonode-api');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ergonode.php', 'ergonode'
        );

        $this->app->singleton(ErgonodeApi::class, fn($app) => new ErgonodeApi(
            config('ergonode.locale'),
            config('ergonode.hostname'),
            config('ergonode.username'),
            config('ergonode.password')
        ));
    }

    public function provides(): array
    {
        return [
            ErgonodeApi::class,
        ];
    }
}
