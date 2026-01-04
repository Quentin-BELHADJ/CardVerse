<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Place d\'échange (Marketplace)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($listings->isEmpty())
                <p class="text-gray-500 text-center">Aucune carte disponible à l'échange pour le moment.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <a href="{{ route('listings.show', $listing->id) }}" class="block p-6 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $listing->card->name }}</h5>
                            <p class="font-normal text-gray-700">
                                <span class="font-bold">Série :</span> {{ $listing->card->collection->name ?? 'N/A' }}<br>
                                <span class="font-bold">État :</span> {{ $listing->condition }}<br>
                                <span class="font-bold">Vendeur :</span> {{ $listing->user->name }}
                            </p>
                            <span class="inline-block bg-green-200 rounded-full px-3 py-1 text-sm font-semibold text-green-700 mr-2 mb-2 mt-2">
                                {{ $listing->status }}
                            </span>
                        </a>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $listings->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>