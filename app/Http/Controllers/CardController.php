<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Collection;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show(Card $card)
    {
        // On charge la collection pour pouvoir afficher le nom de l'extension
        $card->load('collection');
        return view('cards.show', compact('card'));
    }
}
