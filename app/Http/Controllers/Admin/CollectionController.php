<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    // Affiche le formulaire de création
    public function create()
    {
        return view('admin.collections.create');
    }

    // Enregistre la nouvelle collection
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'release_date' => 'required|date',
        ]);

        Collection::create($validated);

        return redirect()->route('collections.index')
            ->with('success', 'Nouvelle collection ajoutée avec succès !');
    }
    // Affiche le formulaire d'édition
    public function edit(Collection $collection)
    {
        return view('admin.collections.edit', compact('collection'));
    }

    // Traite la modification en base de données
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'release_date' => 'nullable|date',
        ]);

        $collection->update($validated);

        // Redirection vers la vue publique de la collection
        return redirect()->route('collections.show', $collection)
            ->with('success', 'Collection mise à jour avec succès !');
    }

    // N'oublie pas la méthode de suppression si tu en as besoin
    // N'oublie pas la méthode de suppression si tu en as besoin
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Collection supprimée.');
    }

    public function import()
    {
        return view('admin.collections.import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json,txt',
        ]);

        $file = $request->file('json_file');
        $json = json_decode(file_get_contents($file), true);

        if (!$json) {
            return back()->withErrors(['json_file' => 'Fichier JSON invalide.']);
        }

        // Validate basic structure
        if (!isset($json['name']) || !isset($json['category'])) {
            return back()->withErrors(['json_file' => 'Format JSON incorrect (name ou category manquants).']);
        }

        // Create Collection
        $collection = Collection::create([
            'name' => $json['name'],
            'category' => $json['category'],
            'release_date' => $json['release_date'] ?? null,
        ]);

        // Create Cards
        $count = 0;
        if (isset($json['cards']) && is_array($json['cards'])) {
            foreach ($json['cards'] as $cardData) {
                $collection->cards()->create([
                    'name' => $cardData['name'] ?? 'Nom inconnu',
                    'rarity' => $cardData['rarity'] ?? 'Commune',
                    'image_url' => $cardData['image_url'] ?? null,
                ]);
                $count++;
            }
        }

        return redirect()->route('collections.index')
            ->with('success', "Collection '{$collection->name}' importée avec $count cartes !");
    }
}
