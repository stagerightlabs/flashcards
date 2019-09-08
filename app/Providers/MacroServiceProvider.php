<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register the macros used in this application.
     *
     * @return void
     */
    public function register()
    {
        // Reference the user's current domain through a request.
        Request::macro('domain', function () {
            return $this->user()->domain ?? null;
        });
    }
}
