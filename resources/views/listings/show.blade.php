<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $listing->card->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Détails de la carte</h3>
                        <p><strong>Extension :</strong> {{ $listing->card->collection->name ?? 'N/A' }}</p>
                        <p><strong>État :</strong> {{ $listing->condition }}</p>
                        <p><strong>Prix :</strong> {{ $listing->price ? $listing->price . ' €' : 'N/A' }}</p>
                        <p><strong>Statut :</strong> {{ $listing->status }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded border">
                        <h3 class="text-lg font-bold mb-2">Propriétaire</h3>
                        <p class="mb-2">{{ $listing->user->name }}</p>

                        @unless(auth()->id() === $listing->user_id)
                            <div class="mt-4 p-3 bg-yellow-100 text-yellow-800 rounded">
                                <strong>Contact pour échange :</strong><br>
                                {{ $listing->user->contact_info ?? 'Aucune information de contact renseignée.' }}
                            </div>
                        @endunless

                        @can('update-listing', $listing)
                            <div class="mt-4">
                                <a href="{{ route('listings.edit', $listing->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Modifier mon annonce
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>