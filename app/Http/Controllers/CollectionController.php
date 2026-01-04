<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Collection::query();

        // 1. Search by Name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Filter by Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // 3. Filter by Date
        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                // Both Start and End -> Range
                $query->whereBetween('release_date', [$request->start_date, $request->end_date]);
            } else {
                // Only Start -> Exact Date 
                $query->where('release_date', $request->start_date);
            }
        } elseif ($request->filled('end_date')) {
            // Only End -> Up to that date
            $query->where('release_date', '<=', $request->end_date);
        }

        $collections = $query->orderBy('release_date', 'desc')->get();
        $categories = Collection::select('category')->distinct()->orderBy('category')->pluck('category');

        // On renvoie vers la vue commune
        return view('collections.index', compact('collections', 'categories'));
    }

    public function show(Collection $collection)
    {
        $collection->load('cards');
        // On renvoie vers la vue commune
        return view('collections.show', compact('collection'));
    }
}
