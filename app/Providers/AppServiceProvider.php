<?php

namespace App\Providers;

use App\Models\Titipan;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->isAdmin());

        Gate::define('lihat-titipan', function (User $user, Titipan $titipan) {
            return $user->isAdmin()
                || $titipan->pembeli_id === $user->id
                || $titipan->driver_id === $user->id;
        });
    }
}
