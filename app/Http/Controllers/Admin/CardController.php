<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Collection;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index() {
        $cards = Card::with('collection')->get();
        return view('admin.cards.index', compact('cards'));
    }

    public function create() {
        $collections = Collection::all();
        return view('admin.cards.create', compact('collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rarity' => 'required|string',
            'collection_id' => 'required|exists:collections,id',
            'image_url' => 'nullable|url',
        ]);

        $card = Card::create($validated);

        // AU LIEU DE : return redirect()->route('admin.cards.index');
        // FAITES CECI :
        return redirect()->route('collections.show', $card->collection_id)
            ->with('success', 'La carte a été ajoutée avec succès !');
    }

    public function edit(Card $card)
    {
        $collections = Collection::all();
        return view('admin.cards.edit', compact('card', 'collections'));
    }

    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rarity' => 'required|string',
            'image_url' => 'nullable|url',
            'collection_id' => 'required|exists:collections,id',
        ]);

        $card->update($validated);

        return redirect()->route('cards.show', $card->id)
            ->with('success', 'La carte a été mise à jour avec succès !');
    }

    public function destroy(Card $card)
    {
        $collectionId = $card->collection_id;

        $card->delete();

        return redirect()->route('collections.show', $collectionId)
            ->with('success', 'La carte a été supprimée avec succès.');
    }
}
