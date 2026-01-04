<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ListingController extends Controller
{
    /**
     * Affiche le formulaire pour ajouter une carte (CREATE)
     * Workshop: Similaire à "Add a new product"
     */
    public function create()
    {
        // On récupère toutes les cartes pour le menu déroulant
        $cards = Card::with('collection')->get(); 
        return view('listings.create', ['cards' => $cards]);
    }

    /**
     * Enregistre la nouvelle carte (STORE)
     * Workshop: Validation + Création
     */
    public function store(Request $request)
    {
        // 1. Validation (Sécurité)
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'condition' => 'required|string',
            'status' => 'required|in:Collection,En vente,En échange',
            'price' => 'nullable|numeric|min:0',
        ]);

        // 2. Création (On force l'ID de l'utilisateur connecté)
        Listing::create([
            'user_id' => Auth::id(), // L'utilisateur connecté
            'card_id' => $validated['card_id'],
            'condition' => $validated['condition'],
            'status' => $validated['status'],
            'price' => $validated['price'],
        ]);

        // 3. Redirection
        return redirect()->route('dashboard')->with('success', 'Carte ajoutée !');
    }

    /**
     * Affiche les échanges (INDEX PUBLIC)
     * Workshop: Similaire à "Show products" avec filtre
     */
    public function indexExchanges()
    {
        // On ne veut que les cartes "En échange"
        // On utilise simplePaginate comme dans le TP
        $listings = Listing::where('status', 'En échange')
                           ->with(['card', 'user']) // Optimisation
                           ->simplePaginate(10);

        return view('listings.exchanges', ['listings' => $listings]);
    }

    /**
     * Affiche une carte spécifique (SHOW)
     * Workshop: Détail produit
     */
    public function show(Listing $listing)
    {
        return view('listings.show', ['listing' => $listing]);
    }

    /**
     * Affiche le formulaire de modification (EDIT)
     * Workshop: "Modify a product"
     */
    public function edit(Listing $listing)
    {
        // Vérification que c'est bien MA carte (Sécurité)
        Gate::authorize('update-listing', $listing);

        return view('listings.edit', ['listing' => $listing]);
    }

    /**
     * Met à jour la carte (UPDATE)
     * Workshop: Méthode PATCH
     */
    public function update(Request $request, Listing $listing)
    {
        Gate::authorize('update-listing', $listing);

        $validated = $request->validate([
            'condition' => 'string', // On peut changer l'état si elle s'abime
            'status' => 'required|in:Collection,En vente,En échange',
            'price' => 'nullable|numeric|min:0',
        ]);

        $listing->update($validated);

        return redirect('/listings/' . $listing->id);
    }

    /**
     * Supprime la carte (DESTROY)
     * Workshop: Méthode DELETE
     */
    public function destroy(Listing $listing)
    {
        Gate::authorize('update-listing', $listing);

        $listing->delete();

        return redirect('/dashboard')->with('success', 'Carte retirée de la collection.');
    }
}