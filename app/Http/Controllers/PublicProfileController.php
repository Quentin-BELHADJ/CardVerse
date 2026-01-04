<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\ListingStatus;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    /**
     * Affiche le profil public d'un utilisateur.
     * Montre ses cartes en vente et ses cartes proposées à l'échange.
     * Si l'utilisateur est banni, renvoie une erreur 404.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        if ($user->is_banned) {
            abort(404);
        }
        // Charge les listings de l'utilisateur qui sont EN VENTE
        $listings = $user->listings()
            ->where('status', ListingStatus::FOR_SALE)
            ->with('card')
            ->latest()
            ->get();

        // Charge les listings de l'utilisateur qui sont EN ÉCHANGE
        $exchanges = $user->listings()
            ->where('status', ListingStatus::FOR_TRADE)
            ->with('card')
            ->latest()
            ->get();

        return view('users.show', compact('user', 'listings', 'exchanges'));
    }
}
