<?php

namespace App\Providers;

use App\Enums\UserProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });
        Gate::define('viewProject', function (User $user, Project|int $project) {
            return $user->hasProjectRole($project, UserProjectRole::ADMIN, UserProjectRole::EDITOR, UserProjectRole::SUBSCRIBER);
        });
        Gate::define('editProject', function (User $user, Project|int $project) {
            return $user->hasProjectRole($project, UserProjectRole::ADMIN, UserProjectRole::EDITOR);
        });
        Gate::define('adminProject', function (User $user, Project|int $project) {
            return $user->hasProjectRole($project, UserProjectRole::ADMIN);
        });
    }
}
