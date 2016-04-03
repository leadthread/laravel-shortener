<?php

namespace Zenapply\Shortener;

use Illuminate\Support\ServiceProvider;

class ShortenerServiceProvider extends ServiceProvider
{
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/shortener.php', 'shortener');

        $this->app->singleton('shortener', function() {
            return new Shortener;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/shortener.php' => base_path('config/shortener.php'),
        ]);   
    }
}