<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Enums\ListingStatus;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal de l'utilisateur.
     * Calcule et affiche les statistiques clés :
     * - Nombre total de cartes dans la collection.
     * - Nombre de cartes mises en vente.
     * - Nombre de cartes proposées à l'échange.
     * - Nombre de collections (sets) uniques commencées.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // 1. Total des cartes possédées (tous status confondus)
        $totalCards = $user->listings()->count();

        // 2. Cartes en vente (Status FOR_SALE)
        $cardsForSale = $user->listings()->where('status', ListingStatus::FOR_SALE)->count();

        // 3. Cartes en échange (Status FOR_TRADE)
        $cardsForTrade = $user->listings()->where('status', ListingStatus::FOR_TRADE)->count();

        // 4. Collections différentes commencées
        // On passe par la relation listing -> card -> collection pour compter les IDs de collection uniques
        $collectionsCount = $user->listings()
            ->with(['card.collection'])
            ->get()
            ->pluck('card.collection_id')
            ->unique()
            ->count();

        return view('dashboard', compact('totalCards', 'cardsForSale', 'cardsForTrade', 'collectionsCount'));
    }
}
