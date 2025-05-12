<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
         // Check for missing files
         if (!file_exists(base_path('.env')) || !file_exists(base_path('.htaccess'))) {
            // Display a custom error page
            abort(503, '.env and/or .htaccess file is missing.');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
