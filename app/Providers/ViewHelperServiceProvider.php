<?php

namespace App\Providers;

use App\Helper\ViewHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ViewHelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind('ViewHelper',function() {
            return new ViewHelper();
        });
    }
}
