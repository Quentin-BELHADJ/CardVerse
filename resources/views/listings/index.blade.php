<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ma Collection') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Messages Flash -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Section Navigation Catalogue -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Gérer ma collection</h2>
                    <p class="mt-1 text-sm text-gray-600">Retrouvez ici toutes les cartes que vous possédez.</p>
                </div>
                <a href="{{ route('collections.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Ajouter des cartes depuis le Catalogue
                </a>
            </div>

            <!-- Liste des Cartes -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Mes Cartes ({{ $listings->count() }})</h2>
                </header>

                <div class="mt-6 space-y-6">
                    @forelse ($listings as $listing)
                        <div x-data="{ 
                                    editMode: false, 
                                    sellMode: false, 
                                    tradeMode: false,
                                    condition: '{{ $listing->condition->value }}',
                                    currentStatus: '{{ $listing->status->value }}'
                                }"
                            class="flex flex-col sm:flex-row items-center justify-between border-b pb-4 last:border-b-0 gap-4">
                            <!-- Card Info -->
                            <div class="flex items-center space-x-4 w-full sm:w-auto">
                                <div
                                    class="w-16 h-24 bg-gray-200 flex items-center justify-center rounded overflow-hidden flex-shrink-0">
                                    @if($listing->card->image_url)
                                        <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}"
                                            class="object-cover w-full h-full">
                                    @else
                                        <span class="text-xs text-gray-500">No Image</span>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-lg font-bold">{{ $listing->card->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $listing->card->rarity }}</p>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        {{ $listing->status->value }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        État actuel: {{ $listing->condition->value }}
                                        @if($listing->status === \App\Enums\ListingStatus::FOR_SALE)
                                            | Prix: {{ $listing->price }} €
                                        @elseif($listing->status === \App\Enums\ListingStatus::FOR_TRADE)
                                            | Échange contre:
                                            {{ $listing->targetCards->isEmpty() ? 'Tout' : $listing->targetCards->count() . ' cartes' }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Area -->
                            <div class="flex flex-col items-end gap-2 w-full sm:w-auto">

                                <!-- Action Buttons (Visible by default) -->
                                <div x-show="!editMode && !sellMode && !tradeMode" class="flex gap-2">
                                    @if($listing->status === \App\Enums\ListingStatus::COLLECTION)
                                        <button @click="editMode = true"
                                            class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">Modifier</button>
                                    @endif

                                    @if($listing->status === \App\Enums\ListingStatus::FOR_SALE)
                                        <form method="POST" action="{{ route('listings.update', $listing) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status"
                                                value="{{ \App\Enums\ListingStatus::COLLECTION->value }}">
                                            <input type="hidden" name="condition" value="{{ $listing->condition->value }}">
                                            <button type="submit"
                                                class="px-3 py-1 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-700">Retirer
                                                de la vente</button>
                                        </form>
                                    @else
                                        <button @click="sellMode = true"
                                            class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Vendre</button>
                                    @endif

                                    @if($listing->status === \App\Enums\ListingStatus::FOR_TRADE)
                                        <form method="POST" action="{{ route('listings.update', $listing) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status"
                                                value="{{ \App\Enums\ListingStatus::COLLECTION->value }}">
                                            <input type="hidden" name="condition" value="{{ $listing->condition->value }}">
                                            <button type="submit"
                                                class="px-3 py-1 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-700">Retirer
                                                des échanges</button>
                                        </form>
                                    @else
                                        <button @click="tradeMode = true"
                                            class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700">Échanger</button>
                                    @endif

                                    <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                                        onsubmit="return confirm('Confirmer la suppression ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">Supprimer</button>
                                    </form>
                                </div>

                                <!-- Modifier Form -->
                                <div x-show="editMode" class="flex gap-2 items-center bg-gray-50 p-2 rounded w-full">
                                    <form method="POST" action="{{ route('listings.update', $listing) }}"
                                        class="flex gap-2 items-center w-full">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $listing->status->value }}">
                                        <select name="condition" class="text-sm border-gray-300 rounded">
                                            @foreach(\App\Enums\ListingCondition::cases() as $case)
                                                <option value="{{ $case->value }}" {{ $listing->condition === $case ? 'selected' : '' }}>{{ $case->value }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Sauver</button>
                                        <button type="button" @click="editMode = false"
                                            class="text-gray-500 text-sm underline">Annuler</button>
                                    </form>
                                </div>

                                <!-- Vendre Form -->
                                <div x-show="sellMode" class="flex gap-2 items-center bg-green-50 p-2 rounded w-full">
                                    <form method="POST" action="{{ route('listings.update', $listing) }}"
                                        class="flex gap-2 items-center w-full">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status"
                                            value="{{ \App\Enums\ListingStatus::FOR_SALE->value }}">
                                        <input type="hidden" name="condition" value="{{ $listing->condition->value }}">
                                        <div class="flex flex-col">
                                            <label class="text-xs text-green-800">Prix (€)</label>
                                            <input type="number" step="0.01" min="0" name="price" value="{{ $listing->price }}"
                                                class="text-sm border-green-300 rounded w-24">
                                        </div>
                                        <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded text-sm">Valider Mise en
                                            Vente</button>
                                        <button type="button" @click="sellMode = false"
                                            class="text-gray-500 text-sm underline">Annuler</button>
                                    </form>
                                </div>

                                <!-- Échanger Form (Complex) -->
                                <div x-show="tradeMode" class="bg-purple-50 p-2 rounded w-full" x-data="{ 
                                            search: '', 
                                            results: [], 
                                            hoveredImage: null,
                                            selected: {{ $listing->targetCards->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
                                            async doSearch() {
                                                if (this.search.length < 2) { this.results = []; return; }
                                                let res = await fetch(`{{ route('cards.search') }}?q=${this.search}`);
                                                this.results = await res.json();
                                            },
                                            add(card) {
                                                if (this.selected.length >= 5) return alert('Max 5 cartes !');
                                                if (!this.selected.find(c => c.id === card.id)) {
                                                    this.selected.push(card);
                                                }
                                                this.search = ''; this.results = [];
                                            },
                                            remove(index) {
                                                this.selected.splice(index, 1);
                                            }
                                         }">
                                    <form method="POST" action="{{ route('listings.update', $listing) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status"
                                            value="{{ \App\Enums\ListingStatus::FOR_TRADE->value }}">
                                        <input type="hidden" name="condition" value="{{ $listing->condition->value }}">

                                        <p class="text-xs text-purple-800 font-bold mb-1">Je cherche (Max 5) :</p>

                                        <!-- Selected List -->
                                        <div class="flex flex-wrap gap-1 mb-2">
                                            <template x-for="(card, index) in selected" :key="card.id">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-200 text-purple-800">
                                                    <span x-text="card.name"></span>
                                                    <button type="button" @click="remove(index)" class="ml-1 text-purple-500 hover:text-purple-700">×</button>
                                                    
                                                    <input type="hidden" name="target_cards[]" :value="card.id">
                                                </span>
                                            </template>
                                            <span x-show="selected.length === 0" class="text-xs text-gray-400 italic">Aucune
                                                carte sélectionnée (Ouvert à tout)</span>
                                        </div>

                                        <!-- Search -->
                                            <div class="relative mb-2 z-30">
                                                <input type="text" x-model="search" @input.debounce.300ms="doSearch()"
                                                    placeholder="Chercher une carte..."
                                                    class="w-full text-sm border-purple-300 rounded">
                                                
                                                <div x-show="results.length > 0"
                                                    class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow-lg max-h-40 overflow-y-auto mt-1">
                                                    <template x-for="res in results" :key="res.id">
                                                        <div @click="add(res)"
                                                            class="px-3 py-2 hover:bg-purple-100 cursor-pointer text-sm flex items-center gap-3 border-b last:border-b-0 border-gray-100">
                                                            <div class="h-10 w-8 bg-gray-100 shrink-0 rounded overflow-hidden border border-gray-200 flex items-center justify-center">
                                                                <template x-if="res.image_url">
                                                                    <img :src="res.image_url" class="w-full h-full object-contain">
                                                                </template>
                                                                <template x-if="!res.image_url">
                                                                    <span class="text-[10px] text-gray-400">?</span>
                                                                </template>
                                                            </div>
                                                            <span x-text="res.name" class="font-medium"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                        <div class="flex gap-2">
                                            <button type="submit"
                                                :disabled="selected.length === 0"
                                                :class="selected.length === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700'"
                                                class="px-3 py-1 text-white rounded text-sm transition-colors">Valider
                                                Échange</button>
                                            <button type="button" @click="tradeMode = false"
                                                class="text-gray-500 text-sm underline">Annuler</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Vous n'avez aucune carte dans votre collection.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>