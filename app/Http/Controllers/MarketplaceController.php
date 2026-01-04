<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    /**
     * Display the marketplace listings.
     */
    public function index(Request $request)
    {
        $query = Listing::where('status', 'En vente')
            ->whereHas('user', function ($q) {
                $q->where('is_banned', false);
            })
            ->with(['card', 'user']);

        // Filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by Collection
        if ($request->filled('collection_id')) {
            $query->whereHas('card', function ($q) use ($request) {
                $q->where('collection_id', $request->collection_id);
            });
        }

        // Search by Name
        if ($request->filled('search')) {
            $query->whereHas('card', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $listings = $query->latest()->simplePaginate(20);
        $collections = \App\Models\Collection::orderBy('name')->get();

        return view('marketplace.index', compact('listings', 'collections'));
    }
}
