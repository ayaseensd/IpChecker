<?php

namespace Ayaseensd\IpChecker;

use Illuminate\Support\ServiceProvider;

class IpCheckerServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (\$this->app->runningInConsole()) {
            \$this->commands([
                Console\CheckIpAccess::class,
            ]);
        }
    }

    public function boot()
    {
        //
    }
}
