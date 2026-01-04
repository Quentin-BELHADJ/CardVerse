<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le produit') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Le formulaire cible la route products.update --}}
                <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH') {{-- Indique à Laravel que c'est une requête PATCH --}}

                    {{-- 1. Nom --}}
                    <div class="mb-4">
                        <label for="name" class="block font-medium text-sm text-gray-700">Nom du Produit</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- 2. Prix --}}
                    <div class="mb-4">
                        <label for="price" class="block font-medium text-sm text-gray-700">Prix (€)</label>
                        <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- 3. Catégorie (Select/Dropdown) --}}
                    <div class="mb-4">
                        <label for="category" class="block font-medium text-sm text-gray-700">Catégorie</label>
                        <select id="category" name="category" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- 4. Description (Textarea) --}}
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- 5. Image (File Input) --}}
                    <div class="mb-4">
                        <label for="image" class="block font-medium text-sm text-gray-700">Image (laisser vide pour garder l'actuelle)</label>
                        <input id="image" type="file" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-1">
                        @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>


                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Mettre à jour le produit
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 pt-4 border-t border-red-300">
                <h3 class="text-xl font-bold text-red-600 mb-4">Danger Zone</h3>

                <p class="text-gray-600 mb-4">Cette action est irréversible. Voulez-vous supprimer ce produit ?</p>

                {{-- Formulaire qui envoie la requête DELETE --}}
                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Êtes-vous absolument certain de vouloir supprimer ce produit ?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Supprimer le produit
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
