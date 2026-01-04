<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de la Collection') }}
            </h2>
            <a href="{{ route('collections.index') }}" class="text-sm text-gray-500 hover:underline">
                &larr; Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border-l-4 border-indigo-500">
                <div class="flex justify-between items-start">
                    <div>
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                            {{ $collection->category }}
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $collection->name }}</h1>
                        <p class="text-gray-500 mt-1">
                            Lancée le : <span
                                class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($collection->release_date)->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>

                    @can('admin-access')
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.collections.edit', $collection) }}"
                               class="bg-amber-100 text-amber-700 px-4 py-2 rounded-md text-sm font-bold hover:bg-amber-200 transition">
                                Modifier la collection
                            </a>
                            <a href="{{ route('admin.cards.create', ['collection_id' => $collection->id]) }}"
                               class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-indigo-700 transition">
                                + Ajouter une carte
                            </a>
                        </div>
                    @endcan
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Cartes de cette collection ({{ $collection->cards->count() }})
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1.5rem;">
                    @forelse($collection->cards as $card)
                        <div class="bg-white p-2 rounded-lg shadow border border-gray-200">
                            <a href="{{ route('cards.show', $card) }}">
                                <img src="{{ $card->image_url ?? 'https://via.placeholder.com/200x280' }}"
                                     alt="{{ $card->name }}"
                                     class="rounded-md w-full h-auto block"
                                     style="max-width: 150px; margin: 0 auto;"> </a>

                            <div class="mt-2 text-center">
                                <p class="font-bold text-gray-800 text-sm truncate">{{ $card->name }}</p>
                                <p class="text-xs text-gray-500">{{ $card->rarity }}</p>
                            </div>

                            @can('admin-access')
                                <div class="mt-2 flex justify-center">
                                    <a href="{{ route('admin.cards.edit', $card) }}"
                                       class="text-xs text-indigo-600 hover:underline">
                                        Modifier
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @empty
                        <div class="col-span-full py-10 text-center text-gray-400 italic">
                            Aucune carte ajoutée.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
