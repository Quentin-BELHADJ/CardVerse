<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Affiche la liste des collections (Sets) de cartes.
     * Permet de filtrer par nom, catégorie et date de sortie.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Collection::query();

        // 1. Recherche par Nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Filtre par Catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Filtre par Date
        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                // Si Date de début ET fin -> Plage de dates
                $query->whereBetween('release_date', [$request->start_date, $request->end_date]);
            } else {
                // Seulement Date de début -> Date exacte
                $query->where('release_date', $request->start_date);
            }
        } elseif ($request->filled('end_date')) {
            // Seulement Date de fin -> Jusqu'à cette date
            $query->where('release_date', '<=', $request->end_date);
        }

        $collections = $query->orderBy('release_date', 'desc')->get();
        $categories = Collection::select('category')->distinct()->orderBy('category')->pluck('category');

        // On renvoie vers la vue commune
        return view('collections.index', compact('collections', 'categories'));
    }

    /**
     * Affiche les détails d'une collection spécifique et les cartes associées.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\View\View
     */
    public function show(Collection $collection)
    {
        $collection->load('cards');
        // On renvoie vers la vue commune
        return view('collections.show', compact('collection'));
    }
}
