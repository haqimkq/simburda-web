<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        //
        if (env(key: 'APP_ENV') !=='local') {
            URL::forceScheme(scheme:'https');
        }else{
            URL::forceScheme(scheme:'http');
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Carbon::setLocale('id');
        // DB::listen(function($query) {
        //     Log::info(
        //         $query->sql,
        //         [
        //             'bindings' => $query->bindings,
        //             'time' => $query->time
        //         ]
        //     );
        // });
    }
}
