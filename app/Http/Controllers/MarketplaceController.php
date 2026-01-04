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

        // Search by card name? (Bonus, but good for "Filtres")
        // "Filtrer les listings par état ou par prix" matches requirements.

        $listings = $query->latest()->simplePaginate(20);

        return view('marketplace.index', compact('listings'));
    }
}
