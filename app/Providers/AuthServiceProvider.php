<?php
namespace App\Providers;

use App\Models\Product; // Importer le modèle Product
use App\Models\User;    // Importer le modèle User
use Illuminate\Support\Facades\Gate; // Déjà importé par défaut, mais vérifiez

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    // ... (propriétés existantes)

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('update-product', function (User $user, Product $product) {
            return $user->id === $product->user_id;
        });

        // Vérifie si l'utilisateur est connecté (non null)
        Gate::define('create-product', function (User $user) {
            // Si $user existe (il est passé s'il est authentifié), il peut créer
            return true;
        });

    }
}
