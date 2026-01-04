<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des Produits') }}
            </h2>

            {{-- Bouton d'ajout protégé par la Gate --}}
            @can('create-product')
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Ajouter un nouveau produit
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @foreach ($products as $product)
                    <div class="mb-4 border-b pb-4">
                        <p class="text-lg font-bold">
                            {{-- Lien vers la page de détail du produit --}}
                            <a href="/products/{{ $product->id }}">
                                {{ $product->name }}
                            </a>
                        </p>
                        <p class="text-gray-600">
                            Prix : **{{ number_format($product->price, 2, ',', ' ') }}€**
                        </p>
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
