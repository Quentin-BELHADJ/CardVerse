<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une Carte au Catalogue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.cards.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nom de la carte')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="collection_id" :value="__('Collection')" />
                        <select name="collection_id" id="collection_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}">{{ $collection->name }} ({{ $collection->category }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="rarity" :value="__('Rareté')" />
                        <x-text-input id="rarity" name="rarity" type="text" class="mt-1 block w-full" placeholder="ex: Ultra Rare, Commune..." required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="image_url" :value="__('URL de l\'image')" />
                        <x-text-input id="image_url" name="image_url" type="text" class="mt-1 block w-full" placeholder="https://..." />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Enregistrer la carte') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
