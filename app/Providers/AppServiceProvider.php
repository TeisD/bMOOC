<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Make a custom blade directive:
         Blade::directive('version', function() {
            $v = 'default';
            $file = base_path() . '/../.revision';
            if(file_exists($file)){
                $v = file_get_contents($file);
            }
            return $v;
         });

        Blade::directive('menu', function($page) {
            $path = app('request')->path();

            if (str_contains($path, $page))
                return 'active';

            return '';

        });
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
