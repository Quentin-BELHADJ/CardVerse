<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Affiche une liste paginée de produits.
     */
    public function index()
    {
        // Utilise simplePaginate pour charger seulement 10 produits par page
        $products = Product::simplePaginate(10);

        return view('products.index', [
            'products' => $products
        ]);
    }

    /**
     * Affiche les détails d'un produit spécifique.
     * Le paramètre $product est automatiquement résolu par Laravel (Route Model Binding).
     */
    public function show(Product $product)
    {
        // $product est l'objet Product que Laravel a trouvé par l'ID dans l'URL.

        // Nous retournons la vue 'products.show' en lui passant le produit.
        return view('products.show', [
            'product' => $product
        ]);
    }

    /**
     * Affiche le formulaire pour éditer le produit.
     */
    public function edit(Product $product)
    {
        // 1. Vérification de l'autorisation via la Gate définie
        // Si l'utilisateur n'a pas le droit, une exception 403 (Unauthorized) est lancée.
        Gate::authorize('update-product', $product);

        // 2. Récupérer toutes les catégories pour le champ select
        $categories = Category::all();

        // 3. Retourner la vue d'édition
        return view('products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Gère la soumission du formulaire et met à jour le produit.
     */
    public function update(Request $request, Product $product)
    {
        // 1. Autorisation
        Gate::authorize('update-product', $product);

        // 2. Validation
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'price' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'category' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,png'],
        ]);

        // Initialisation des données à mettre à jour
        $dataToUpdate = [
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category'],
        ];

        // 3. Gestion du fichier (Image)
        if ($request->hasFile('image')) {
            // Sauvegarder la nouvelle image
            $file = $request->file('image');
            // Stocke dans public/img/
            $path = $file->store('img', 'public_uploads');

            // Ajouter le nouveau chemin à la mise à jour
            $dataToUpdate['image'] = $path;

            // OPTIONNEL : Suppression de l'ancienne image pour libérer de l'espace
            // if ($product->image && $product->image != 'default.jpg') {
            //     Storage::disk('public_uploads')->delete($product->image);
            // }
        }

        // 4. Mise à jour du produit
        $product->update($dataToUpdate);

        // 5. Redirection
        return redirect()->route('products.show', $product)
            ->with('status', 'Produit mis à jour et validé avec succès.');
    }

    /**
     * Supprime le produit spécifié après vérification de l'autorisation.
     */
    public function destroy(Product $product)
    {
        // 1. Vérification de l'autorisation (utilise la même Gate que pour la mise à jour)
        Gate::authorize('update-product', $product);

        // 2. Suppression du produit
        $product->delete();

        // 3. Redirection vers la liste des produits avec un message de succès
        return redirect()->route('products.index')
            ->with('status', 'Le produit a été supprimé avec succès.');
    }

    /**
     * Affiche le formulaire pour créer un nouveau produit.
     */
    public function create()
    {
        // 1. Vérification de l'autorisation (si non connecté, la Gate échouera)
        Gate::authorize('create-product');

        // 2. Récupérer toutes les catégories pour le champ select
        $categories = Category::all();

        // 3. Retourner la vue de création
        return view('products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Stocke le nouveau produit dans la base de données.
     */
    public function store(Request $request)
    {
        // 1. Autorisation (vérifie si l'utilisateur est connecté)
        Gate::authorize('create-product');

        // 2. Validation (similaire à update, mais tous les champs sont requis)
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'price' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'category' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,png'], // L'image est requise à la création
        ]);

        // 3. Gestion du fichier (Image)
        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('img', 'public_uploads');
        }

        // 4. Création et stockage du produit
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category'],
            'image' => $path,
            'user_id' => Auth::id(), // IMPORTANT : Assigne le produit à l'utilisateur connecté
        ]);

        // 5. Redirection vers la liste des produits
        return redirect()->route('products.index')
            ->with('status', 'Produit créé avec succès.');
    }
}
