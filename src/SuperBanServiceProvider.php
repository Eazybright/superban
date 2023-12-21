<?php

namespace Eazybright\SuperBan;

use Illuminate\Support\ServiceProvider;

class SuperBanServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/superban.php', 'superban'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/superban.php' => config_path('superban.php'),
        ]);
    }
}
