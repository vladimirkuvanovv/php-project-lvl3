<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
/*        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }

        Telescope::ignoreMigrations();*/
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
/*        DB::listen(function ($query) {
            dump($query->sql);
//            dump($query->bindings);
//            dump($query->time);
        });*/
    }
}
