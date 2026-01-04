<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la Collection') }} : {{ $collection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.collections.update', $collection) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Indispensable pour la modification --}}

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="name" :value="__('Nom de la Collection')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $collection->name)" required/>
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Catégorie (ex: Pokémon, Magic...)')"/>
                            <x-text-input id="category" name="category" type="text" class="mt-1 block w-full"
                                          :value="old('category', $collection->category)" required/>
                        </div>

                        <div>
                            <x-input-label for="release_date" :value="__('Date de sortie')"/>
                            <x-text-input id="release_date" name="release_date" type="date" class="mt-1 block w-full"
                                          :value="old('release_date', $collection->release_date)"/>
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Enregistrer les modifications') }}</x-primary-button>
                            <a href="{{ route('collections.index') }}"
                               class="text-sm text-gray-600 underline">Annuler</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
