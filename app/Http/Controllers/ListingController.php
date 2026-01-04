<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Enums\ListingStatus;
use App\Enums\ListingCondition;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    /**
     * Affiche la liste des cartes appartenant à l'utilisateur connecté.
     * Cette méthode récupère les listings de l'utilisateur, charge les relations 'card',
     * et les retourne à la vue 'listings.index'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupère les listings de l'utilisateur authentifié, triés par date de création décroissante
        $listings = Auth::user()->listings()->with('card')->latest()->get();
        return view('listings.index', compact('listings'));
    }

    /**
     * Enregistre une nouvelle carte dans la collection de l'utilisateur.
     * Crée une nouvelle instance de Listing attachée à l'utilisateur et à la carte spécifiée.
     * Le statut par défaut est 'COLLECTION' (pas en vente).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'condition' => ['required', Rule::enum(ListingCondition::class)],
        ]);

        Listing::create([
            'user_id' => Auth::id(),
            'card_id' => $request->card_id,
            'status' => ListingStatus::COLLECTION, // Statut par défaut : dans la collection personnelle
            'condition' => $request->condition,
            'price' => null, // Pas de prix initialement car pas en vente
        ]);

        return back()->with('success', 'Carte ajoutée à votre collection !');
    }

    /**
     * Met à jour les informations d'une carte (Listing) existante.
     * Gère le changement de statut (Collection, Vente, Échange), le prix,
     * et la liste des cartes recherchées pour un échange.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Listing $listing)
    {
        // 1. Sécurité : Vérifie que l'utilisateur est bien le propriétaire de la carte
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validation des données du formulaire
        $validated = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\ListingStatus::class)],
            'condition' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\ListingCondition::class)],
            'price' => 'nullable|numeric|min:0',
            'target_cards' => 'nullable|array|max:5', // Maximum 5 cartes cibles pour l'échange
            'target_cards.*' => 'exists:cards,id',
        ]);

        // 3. Mise à jour des informations de base
        // Si le statut n'est pas 'En vente', on force le prix à null
        $price = ($validated['status'] === \App\Enums\ListingStatus::FOR_SALE->value) ? ($validated['price'] ?? null) : null;

        $listing->update([
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'price' => $price,
        ]);

        // 4. Logique spécifique pour le mode Échange
        if ($listing->status === \App\Enums\ListingStatus::FOR_TRADE) {
            // Synchronise les cartes recherchées (relation ManyToMany)
            // Si aucune carte n'est sélectionnée, cela signifie "Ouvert à tout"
            $listing->targetCards()->sync($validated['target_cards'] ?? []);
        } else {
            // Si la carte n'est plus en échange, on supprime les préférences d'échange
            $listing->targetCards()->detach();
        }

        return back()->with('success', 'Carte mise à jour avec succès !');
    }

    /**
     * Affiche la "Zone d'Échange" publique.
     * Liste toutes les cartes proposées à l'échange par les autres utilisateurs.
     * Inclut des filtres pour rechercher par nom ou par catégorie (set).
     * Filtre automatiquement les utilisateurs bannis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function indexExchanges(Request $request)
    {
        // Construction de la requête de base : Cartes avec statut 'FOR_TRADE'
        $query = Listing::where('status', \App\Enums\ListingStatus::FOR_TRADE)
            // Filtre : Exclure les listings des utilisateurs bannis
            ->whereHas('user', function ($q) {
                $q->where('is_banned', false);
            })
            ->with(['card', 'user']);

        // Filtre : Par Catégorie (Collection/Set)
        if ($request->filled('category')) {
            $query->whereHas('card.collection', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Filtre : Recherche textuelle par nom de carte
        if ($request->filled('search')) {
            $query->whereHas('card', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination des résultats (12 par page)
        $listings = $query->latest()->paginate(12);

        // Récupération des catégories distinctes pour le menu déroulant de filtre
        $categories = \App\Models\Collection::select('category')->distinct()->orderBy('category')->pluck('category');

        return view('listings.exchanges', compact('listings', 'categories'));
    }

    /**
     * Supprime une carte de la collection de l'utilisateur.
     * Vérifie l'autorisation avant de procéder à la suppression.
     *
     * @param  \App\Models\Listing  $listing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Listing $listing)
    {
        // Sécurité : Seul le propriétaire peut supprimer
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        $listing->delete();

        return back()->with('success', 'Carte retirée de votre collection.');
    }
}
