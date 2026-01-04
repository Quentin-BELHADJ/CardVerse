<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- Affichage de l'image --}}
                <div class="mb-6">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-1/3 h-auto object-cover rounded-lg shadow-md">
                </div>

                {{-- Détails du produit --}}
                <h1 class="text-3xl font-extrabold mb-4">{{ $product->name }}</h1>

                <div class="text-2xl text-green-600 font-bold mb-4">
                    Prix : {{ number_format($product->price, 2, ',', ' ') }} €
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold border-b pb-1 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                </div>

                {{-- Informations liées (grâce aux relations) --}}
                <div class="grid grid-cols-2 gap-4 border-t pt-4">
                    <div>
                        <span class="font-semibold">Catégorie :</span>
                        {{-- Accès à la relation Category --}}
                        <span class="text-blue-600">{{ $product->category->name }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">Vendu par :</span>
                        {{-- Accès à la relation User (Vendeur) --}}
                        <span class="text-indigo-600">{{ $product->user->name }}</span>
                    </div>
                </div>

                {{-- resources/views/products/show.blade.php (près des détails) --}}
                {{-- ... --}}

                {{-- Bouton/Lien d'édition protégé par la Gate --}}
                @can('update-product', $product)
                    <div class="mt-4">
                        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Modifier le produit
                        </a>
                    </div>
                @endcan

                {{-- ... --}}

                {{-- Lien de retour --}}
                <div class="mt-8">
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        &larr; Retour à la liste des produits
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

