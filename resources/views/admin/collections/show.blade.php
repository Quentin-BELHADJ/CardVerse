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

                {{-- Ici on définit que chaque colonne fait au minimum 160px et au maximum 200px --}}
                <div class="grid justify-center gap-6"
                     style="grid-template-columns: repeat(auto-fill, minmax(160px, 200px));">

                    @forelse($collection->cards as $card)
                        <div
                            class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 group relative hover:shadow-lg transition-all duration-300 w-48">

                            <a href="{{ route('cards.show', $card) }}"
                               class="block overflow-hidden rounded-lg bg-gray-100">
                                <img src="{{ $card->image_url ?? 'https://via.placeholder.com/200x280?text=No+Image' }}"
                                     alt="{{ $card->name }}"
                                     class="w-48 object-contain transform group-hover:scale-105 transition duration-500">
                            </a>

                            <div class="mt-3 px-1">
                                <p class="font-bold text-gray-900 text-xs truncate" title="{{ $card->name }}">
                                    {{ $card->name }}
                                </p>
                                <div class="flex justify-between items-center mt-1">
                    <span class="text-[9px] uppercase font-bold text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded">
                        {{ $card->rarity }}
                    </span>
                                </div>
                            </div>

                            @can('admin-access')
                                <div
                                    class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.cards.edit', $card) }}"
                                       class="bg-white/90 p-2 rounded-full shadow-md text-gray-700 hover:text-orange-600 border border-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @empty
                        <div
                            class="col-span-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-12 text-center text-gray-400 italic">
                            Aucune carte n'a encore été ajoutée à cette collection.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
