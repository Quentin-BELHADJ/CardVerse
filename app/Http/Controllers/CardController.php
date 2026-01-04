<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Affiche les détails d'une carte spécifique.
     *
     * @param  \App\Models\Card  $card
     * @return \Illuminate\View\View
     */
    public function show(Card $card)
    {
        return view('cards.show', compact('card'));
    }

    /**
     * Recherche des cartes par nom via AJAX (API).
     * Renvoie les résultats au format JSON (pour les menus déroulants, etc.).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        // Nécessite au moins 2 caractères pour lancer la recherche
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cards = Card::where('name', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'image_url']);

        return response()->json($cards);
    }
}
