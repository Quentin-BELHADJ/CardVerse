<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });

        // On vérifie à chaque requête si l'utilisateur est banni
        view()->composer('*', function () {
            if (Auth::check() && Auth::user()->is_banned) {
                Auth::logout();

                // On détruit la session pour forcer la déconnexion
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Votre compte a été suspendu par un administrateur.'])
                    ->throwResponse();
            }
        });
    }
}
