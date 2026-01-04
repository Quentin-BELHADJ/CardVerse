<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Affiche la liste publique des cartes mises en vente (Marketplace).
     * Gère plusieurs filtres : prix min/max, état, collection cible, catégorie, et recherche par nom.
     * Exclut automatiquement les vendeurs bannis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Requête de base : Cartes "En vente" uniquement
        $query = Listing::where('status', 'En vente') // Assurez-vous que 'En vente' correspond bien à l'Enum si vous l'utilisez
            // Filtre de sécurité : Masquer les utilisateurs bannis
            ->whereHas('user', function ($q) {
                $q->where('is_banned', false);
            })
            ->with(['card', 'user']);

        // Filtre : Prix minimum
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtre : Prix maximum
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtre : État de la carte (Neuf, Bon, etc.)
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filtre : Par Collection spécifique (Set précis)
        if ($request->filled('collection_id')) {
            $query->whereHas('card', function ($q) use ($request) {
                $q->where('collection_id', $request->collection_id);
            });
        }

        // Filtre : Par Catégorie (Type de set)
        if ($request->filled('category')) {
            $query->whereHas('card.collection', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Filtre : Recherche par nom de carte
        if ($request->filled('search')) {
            $query->whereHas('card', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination simple pour la performance
        $listings = $query->latest()->simplePaginate(20);

        // Récupération des données pour les menus déroulants de filtre
        $collections = \App\Models\Collection::orderBy('name')->get();
        $categories = \App\Models\Collection::select('category')->distinct()->orderBy('category')->pluck('category');

        return view('marketplace.index', compact('listings', 'collections', 'categories'));
    }
}

