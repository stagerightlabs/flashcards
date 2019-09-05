<?php

namespace App\Providers;

use App\Ulid;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the ULID singleton
        $this->app->singleton(\App\Ulid::class, function ($app) {
            return new Ulid();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
