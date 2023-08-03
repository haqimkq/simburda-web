<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;



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
    public function boot(\Illuminate\Http\Request $request)
    {
        //
        Carbon::setLocale('id');
        // Builder::macro('whereRelationIn', function ($relation, $column, $array) {
        //     $this->whereHas(
        //         $relation, fn($q) => $q->whereIn($column, $array)
        //     );
        // });
        // if (!empty( env('NGROK_URL') ) && $request->server->has('HTTP_X_ORIGINAL_HOST')) {
        //     $this->app['url']->forceRootUrl(env('NGROK_URL'));
        // }
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
