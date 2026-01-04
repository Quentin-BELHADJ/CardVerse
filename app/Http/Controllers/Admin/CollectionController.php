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
    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('collections.index')->with('success', 'Collection supprimée.');
    }
}
