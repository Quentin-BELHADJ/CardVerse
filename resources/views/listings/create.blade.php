<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter une carte à ma collection
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="/listings" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label>Quelle carte possédez-vous ?</label>
                    <select name="card_id" class="block mt-1 w-full">
                        @foreach($cards as $card)
                            <option value="{{ $card->id }}">{{ $card->name }} ({{ $card->collection->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label>État de la carte :</label>
                    <select name="condition" class="block mt-1 w-full">
                        <option value="Parfait état">Parfait état</option>
                        <option value="Comme neuf">Comme neuf</option>
                        <option value="Bon état">Bon état</option>
                        <option value="Légers défauts">Légers défauts</option>
                        <option value="Abimé">Abimé</option>
                        <option value="Très abimé">Très abimé</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Statut :</label>
                    <select name="status" class="block mt-1 w-full">
                        <option value="Collection">Collection privée (Gardée)</option>
                        <option value="En échange">Disponible à l'échange</option>
                        <option value="En vente">En vente</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Prix (si vente) :</label>
                    <input type="number" step="0.01" name="price" class="block mt-1 w-full" placeholder="0.00">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Ajouter
                </button>
            </form>
        </div>
    </div>
</x-app-layout>