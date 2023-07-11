<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\DeliveryOrder;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Gate::define('update-post', function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });

        Gate::define('USER', function (User $user) {
            return $user->role === 'USER';
        });

        Gate::define('ADMIN', function (User $user) {
            return $user->role === 'ADMIN';
        });

        Gate::define('ADMIN_GUDANG', function (User $user) {
            return $user->role === 'ADMIN_GUDANG';
        });

        Gate::define('PROJECT_MANAGER', function (User $user) {
            return $user->role === 'PROJECT_MANAGER';
        });
        
        Gate::define('SET_MANAGER', function (User $user) {
            return $user->role === 'SET_MANAGER';
        });

        Gate::define('PURCHASING', function (User $user) {
            return $user->role === 'PURCHASING';
        });

        Gate::define('LOGISTIC', function (User $user) {
            return $user->role === 'LOGISTIC';
        });

        Gate::define('SUPERVISOR', function (User $user) {
            return $user->role === 'SUPERVISOR';
        });

        Gate::define('cetak-download-do', function (User $user, DeliveryOrder $deliveryOrder) {
            return $user->id === $deliveryOrder->purchasing_id || $user->role === 'ADMIN';
        });
    }
}
