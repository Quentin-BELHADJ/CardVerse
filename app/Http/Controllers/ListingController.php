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
    public function update(Request $request, Listing $listing)
    {
        // Ensure the user owns this listing
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        // Logic based on action
        $status = $request->status ? ListingStatus::tryFrom($request->status) : $listing->status;

        // If "Retirer de la vente" or "Retirer des échanges", status goes back to Collection
        // But the UI might just send the desired status directly.
        // Let's validate fully.

        $validated = $request->validate([
            'status' => ['required', Rule::enum(ListingStatus::class)],
            'condition' => ['required', Rule::enum(ListingCondition::class)],
            'price' => 'nullable|numeric|min:0|required_if:status,' . ListingStatus::FOR_SALE->value,
            'target_cards' => 'nullable|array|max:5',
            'target_cards.*' => 'exists:cards,id',
        ]);

        // Specific logic:
        if ($validated['status'] === ListingStatus::COLLECTION) {
            $validated['price'] = null;
            // Clear target cards handled below
        }

        $listing->update([
            'status' => $validated['status'],
            'condition' => $validated['condition'],
            'price' => $validated['price'] ?? null,
        ]);

        // Sync target cards if provided, or clear if status is not Trade
        if ($validated['status'] === ListingStatus::FOR_TRADE && isset($validated['target_cards'])) {
            $listing->targetCards()->sync($validated['target_cards']);
        } else {
            $listing->targetCards()->detach();
        }

        return back()->with('success', 'Fiche mise à jour !');
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
