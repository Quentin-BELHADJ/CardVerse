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
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user's listings
        $listings = Auth::user()->listings()->with('card')->latest()->get();
        return view('listings.index', compact('listings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'condition' => ['required', Rule::enum(ListingCondition::class)],
        ]);

        // Check if user already has this card listed? 
        // Requirements technically allow multiple copies, so we won't restrict it unless specified.
        // But usually "Add a card to collection" implies creating a new Listing entry.

        Listing::create([
            'user_id' => Auth::id(),
            'card_id' => $request->card_id,
            'status' => ListingStatus::COLLECTION, // Default status
            'condition' => $request->condition, // Casts handle string to Enum conversion usually, or valid value
            'price' => null, // Not for sale initially
        ]);

        return back()->with('success', 'Carte ajoutée à votre collection !');
    }

    /**
     * Update the specified resource in storage.
     */
    // Dans app/Http/Controllers/ListingController.php

public function update(Request $request, Listing $listing)
    {
        // 1. Sécurité
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validation
        $validated = $request->validate([
            'status' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\ListingStatus::class)],
            'condition' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\ListingCondition::class)],
            'price' => 'nullable|numeric|min:0',
            'target_cards' => 'nullable|array|max:5',
            'target_cards.*' => 'exists:cards,id',
        ]);

        // 3. Mise à jour des infos de base
        $listing->update([
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'price' => ($validated['status'] === \App\Enums\ListingStatus::FOR_SALE->value) ? $validated['price'] : null,
        ]);

        // 4. LOGIQUE D'ÉCHANGE (La partie qui posait problème)
        
        // On utilise la comparaison stricte avec l'Enum (grâce au cast du Modèle)
        if ($listing->status === \App\Enums\ListingStatus::FOR_TRADE) {
            
            // MAGIE : Si 'target_cards' est rempli (ex: ID 6), on sauvegarde.
            // Si c'est vide ou null, on met [] -> "Ouvert à toute proposition".
            $listing->targetCards()->sync($validated['target_cards'] ?? []);
            
        } else {
            // Si on n'est plus en échange, on nettoie
            $listing->targetCards()->detach();
        }

        return back()->with('success', 'Carte mise à jour avec succès !');
    }
    /**
     * Affiche la "Zone d'Échange" publique (Cartes des autres utilisateurs).
     */
    public function indexExchanges()
    {
        // 1. On récupère TOUS les listings avec le statut "En échange"
        // 2. On exclut ceux de l'utilisateur connecté (on ne s'échange pas à soi-même)
        // 3. On charge les relations 'card' et 'user' pour l'affichage
        $listings = Listing::where('status', \App\Enums\ListingStatus::FOR_TRADE)
                        ->where('user_id', '!=', Auth::id()) 
                        ->with(['card', 'user'])
                        ->latest()
                        ->paginate(12);

        return view('listings.exchanges', compact('listings'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        $listing->delete();

        return back()->with('success', 'Carte retirée de votre collection.');
    }
}
