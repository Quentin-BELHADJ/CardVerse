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
                    
                    <!-- Search -->
                     <div class="flex-grow min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Nom de la carte...">
                    </div>

                    <!-- Collection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Collection</label>
                        <select name="collection_id" class="mt-1 block w-48 border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes les collections</option>
                            @foreach($collections as $col)
                                <option value="{{ $col->id }}" {{ request('collection_id') == $col->id ? 'selected' : '' }}>
                                    {{ $col->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @forelse ($listings as $listing)
                    <div class="bg-white p-2 rounded-lg shadow border border-gray-200 flex flex-col justify-between">
                        <!-- Image -->
                        <div class="flex justify-center mb-2">
                             @if($listing->card->image_url)
                                <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="rounded-md w-full h-auto object-contain" style="max-height: 200px;">
                            @else
                                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 rounded-md">
                                    No Image
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="p-2 text-center">
                            <h3 class="font-bold text-gray-900 truncate text-sm" title="{{ $listing->card->name }}">{{ $listing->card->name }}</h3>
                            <p class="text-xs text-gray-500 mb-2">{{ $listing->card->rarity }}</p>
                            
                            <div class="bg-gray-50 rounded p-2 mb-2">
                                <p class="text-lg font-bold text-indigo-600">{{ number_format($listing->price, 2) }} €</p>
                                <p class="text-xs text-gray-500">{{ $listing->condition->value }}</p>
                            </div>

                            <p class="text-xs text-gray-400 mb-3">Vendeur: <span class="font-medium text-gray-600">{{ $listing->user->name }}</span></p>

                            <a href="{{ route('users.show', $listing->user) }}" class="block w-full text-center px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded hover:bg-gray-800 transition">
                                Voir le profil
                            </a>
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
