<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

//        $this->registerPolicies();

        Gate::define('update-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('delete-post', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });
    }
}