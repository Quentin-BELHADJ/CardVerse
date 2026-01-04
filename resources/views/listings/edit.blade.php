<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier : {{ $listing->card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('listings.update', $listing->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">État :</label>
                        <select name="condition" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            @foreach(['Parfait état', 'Comme neuf', 'Bon état', 'Légers défauts', 'Abimé', 'Très abimé'] as $cond)
                                <option value="{{ $cond }}" {{ $listing->condition == $cond ? 'selected' : '' }}>
                                    {{ $cond }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Statut :</label>
                        <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            <option value="Collection" {{ $listing->status == 'Collection' ? 'selected' : '' }}>Collection (Privé)</option>
                            <option value="En échange" {{ $listing->status == 'En échange' ? 'selected' : '' }}>En échange</option>
                            <option value="En vente" {{ $listing->status == 'En vente' ? 'selected' : '' }}>En vente</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Prix :</label>
                        <input type="number" step="0.01" name="price" value="{{ $listing->price }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Mettre à jour
                        </button>
                        
                        <a href="{{ route('listings.show', $listing->id) }}" class="text-gray-600 hover:text-gray-800">
                            Annuler
                        </a>

                        <button form="delete-form" type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-auto">
                            Retirer de la collection
                        </button>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('listings.destroy', $listing->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</x-app-layout>