<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::all();
        // On renvoie vers la vue commune
        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        $collection->load('cards');
        // On renvoie vers la vue commune
        return view('collections.show', compact('collection'));
    }
}
