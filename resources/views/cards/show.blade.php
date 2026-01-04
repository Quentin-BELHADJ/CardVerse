<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center px-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $card->name }} <span class="text-gray-400 text-sm ml-2">#{{ $card->number ?? '?' }}</span>
            </h2>
            <a href="{{ route('collections.show', $card->collection_id) }}"
               class="text-sm text-indigo-600 hover:underline">
                &larr; Retour
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <div class="flex flex-col md:flex-row gap-8 items-start">

                    <div class="w-full md:w-auto flex-shrink-0">
                        <img src="{{ $card->image_url ?? 'https://via.placeholder.com/400x560' }}"
                             alt="{{ $card->name }}"
                             class="rounded-lg shadow-md mx-auto"
                             style="width: 250px; height: auto;">
                    </div>

                    <div class="flex-1 space-y-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $card->name }}</h1>
                            <p class="text-indigo-600 font-medium">{{ $card->collection->name }}</p>
                        </div>

                        <div class="border-t border-b border-gray-100 py-3">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Rareté</p>
                            <p class="text-gray-700">{{ $card->rarity }}</p>
                        </div>

                        @if($card->description)
                            <div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">Description / Effet</p>
                                <p class="text-gray-600 text-sm italic leading-snug">"{{ $card->description }}"</p>
                            </div>
                        @endif

                        @can('admin-access')
                            <div class="mt-6 pt-4 border-t border-gray-100 flex gap-2">
                                <a href="{{ route('admin.cards.edit', $card) }}">
                                    <x-secondary-button class="text-xs">
                                        Modifier
                                    </x-secondary-button>
                                </a>
                                <form action="{{ route('admin.cards.destroy', $card) }}" method="POST"
                                      onsubmit="return confirm('Supprimer ?');">
                                    @csrf @method('DELETE')
                                    <x-danger-button class="text-xs">
                                        Supprimer
                                    </x-danger-button>
                                </form>
                            </div>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
