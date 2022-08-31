<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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

        Gate::define('user', function (User $user) {
            return $user->role === 'user';
        });

        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('admin-gudang', function (User $user) {
            return $user->role === 'admin gudang';
        });

        Gate::define('project-manager', function (User $user) {
            return $user->role === 'project manager';
        });

        Gate::define('purchasing', function (User $user) {
            return $user->role === 'purchasing';
        });

        Gate::define('logistic', function (User $user) {
            return $user->role === 'logistic';
        });

        Gate::define('supervisor', function (User $user) {
            return $user->role === 'supervisor';
        });

        // Gate::define('delete-user', function (User $user, Post $post) {
        //     return $user->id === $post->user_id;
        // });
    }
}
