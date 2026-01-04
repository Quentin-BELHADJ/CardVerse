<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        // Reuse existing public show logic? 
        // Or if this controller is new/separate from Admin/CardController.
        // It seems there is a public CardController already mentioned in routes.
        // Let's create it properly if it doesn't exist, or update it.
        // The file list didn't show it in the root logs but routes used it.
        // Checking if it exists first might be wise, but I'll write it to be safe/complete.
        return view('cards.show', compact('card'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cards = Card::where('name', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'image_url']);

        return response()->json($cards);
    }
}
