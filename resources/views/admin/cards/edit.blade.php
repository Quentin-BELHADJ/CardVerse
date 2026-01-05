<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la carte : ') }} {{ $card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('admin.cards.update', $card) }}">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Nom de la carte')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $card->name)" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="rarity" :value="__('Rareté')" />
                        <x-text-input id="rarity" class="block mt-1 w-full" type="text" name="rarity"
                            :value="old('rarity', $card->rarity)" required />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="image_url" :value="__('URL de l\'image')" />
                        <x-text-input id="image_url" class="block mt-1 w-full" type="text" name="image_url"
                            :value="old('image_url', $card->image_url)" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="collection_id" :value="__('Collection')" />
                        <select name="collection_id" id="collection_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            @foreach($collections as $collection)
                                <option value="{{ $collection->id }}" @selected(old('collection_id', $card->collection_id) == $collection->id)>
                                    {{ $collection->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('cards.show', $card) }}"
                            class="text-sm text-gray-600 underline hover:text-gray-900">
                            {{ __('Annuler') }}
                        </a>
                        <x-primary-button>
                            {{ __('Enregistrer les modifications') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>