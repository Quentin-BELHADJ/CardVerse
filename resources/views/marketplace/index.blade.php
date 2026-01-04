<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filtres -->
            <div class="p-4 bg-white shadow sm:rounded-lg">
                <form method="GET" action="{{ route('marketplace.index') }}" class="flex flex-wrap gap-4 items-end">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Condition</label>
                        <select name="condition" class="mt-1 block w-40 border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes</option>
                            @foreach(\App\Enums\ListingCondition::cases() as $case)
                                <option value="{{ $case->value }}" {{ request('condition') == $case->value ? 'selected' : '' }}>{{ $case->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix Min</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="mt-1 block w-24 border-gray-300 rounded-md shadow-sm" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix Max</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="mt-1 block w-24 border-gray-300 rounded-md shadow-sm" placeholder="1000">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filtrer</button>
                    <a href="{{ route('marketplace.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                </form>
            </div>

            <!-- Grille des Offres -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($listings as $listing)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                         <!-- Image -->
                        <div class="h-48 bg-gray-200 w-full flex items-center justify-center overflow-hidden">
                            @if($listing->card->image_url)
                                <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="object-contain w-full h-full">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 truncate">{{ $listing->card->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $listing->card->rarity }}</p>
                                </div>
                                <span class="font-bold text-lg text-indigo-600">
                                    {{ number_format($listing->price, 2) }} €
                                </span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-sm text-gray-600"><span class="font-medium">État:</span> {{ $listing->condition->value }}</p>
                                <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Vendeur:</span> {{ $listing->user->name }}</p>
                                
                                <div class="mt-4">
                                     <a href="{{ route('users.show', $listing->user) }}" class="block w-full text-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded hover:bg-gray-800">
                                        Voir le profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Aucune offre trouvée pour ces critères.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $listings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
