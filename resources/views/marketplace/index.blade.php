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
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Nom de la carte...">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                        <select name="category" class="mt-1 block w-40 border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
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
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                            class="mt-1 block w-24 border-gray-300 rounded-md shadow-sm" placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix Max</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                            class="mt-1 block w-24 border-gray-300 rounded-md shadow-sm" placeholder="1000">
                    </div>

                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filtrer</button>
                    <a href="{{ route('marketplace.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                </form>
            </div>

            <!-- Grille des Offres -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
                @forelse ($listings as $listing)
                    <div class="bg-white p-2 rounded-lg shadow border border-gray-200 flex flex-col justify-between">
                        <!-- Image -->
                        <div class="flex justify-center mb-2">
                            @if($listing->card->image_url)
                                <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}"
                                    class="rounded-md w-full h-auto object-contain" style="max-height: 200px;">
                            @else
                                <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 rounded-md">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-2 text-center">
                            <h3 class="font-bold text-gray-900 truncate text-sm" title="{{ $listing->card->name }}">
                                {{ $listing->card->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">{{ $listing->card->rarity }}</p>

                            <div class="bg-gray-50 rounded p-2 mb-2">
                                <p class="text-lg font-bold text-indigo-600">{{ number_format($listing->price, 2) }} €</p>
                                <p class="text-xs text-gray-500">{{ $listing->condition->value }}</p>
                            </div>

                            <div x-data="{ showContact: false }" class="mt-2 text-left">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm truncate mr-2">
                                        <span class="text-gray-500">Vendeur:</span>
                                        <span class="font-medium text-gray-900"
                                            title="{{ $listing->user->name }}">{{ $listing->user->name }}</span>
                                    </div>

                                    @if(auth()->id() !== $listing->user->id)
                                        <button @click="showContact = !showContact"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold focus:outline-none whitespace-nowrap">
                                            <span x-show="!showContact">Contacter</span>
                                            <span x-show="showContact">Fermer</span>
                                        </button>
                                    @endif
                                </div>

                                <div x-show="showContact"
                                    class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded text-sm text-indigo-900 transition-all text-left"
                                    style="display: none;">

                                    <p class="font-bold mb-2 border-b border-indigo-200 pb-1">Moyens de contact :</p>

                                    <div class="space-y-2">
                                        @if($listing->user->discord_handle)
                                            <div class="flex items-center gap-2 w-full">
                                                <span class="font-semibold w-16 shrink-0">Discord:</span>
                                                <button type="button" data-handle="{{ $listing->user->discord_handle }}" x-data="{ 
                                                                        copied: false,
                                                                        copyToClipboard() {
                                                                            const text = $el.dataset.handle;
                                                                            if (navigator.clipboard) {
                                                                                navigator.clipboard.writeText(text).then(() => {
                                                                                    this.copied = true;
                                                                                    setTimeout(() => this.copied = false, 2000);
                                                                                }).catch(() => this.fallbackCopy(text));
                                                                            } else {
                                                                                this.fallbackCopy(text);
                                                                            }
                                                                        },
                                                                        fallbackCopy(text) {
                                                                            const textArea = document.createElement('textarea');
                                                                            textArea.value = text;
                                                                            textArea.style.position = 'fixed';
                                                                            textArea.style.left = '-9999px';
                                                                            document.body.appendChild(textArea);
                                                                            textArea.focus();
                                                                            textArea.select();
                                                                            try {
                                                                                document.execCommand('copy');
                                                                                this.copied = true;
                                                                                setTimeout(() => this.copied = false, 2000);
                                                                            } catch (err) {
                                                                                console.error('Fallback copy failed', err);
                                                                                alert('Copie impossible');
                                                                            }
                                                                            document.body.removeChild(textArea);
                                                                        }
                                                                    }" @click="copyToClipboard()"
                                                    class="bg-white px-2 py-0.5 rounded border border-indigo-200 font-mono text-xs flex items-center gap-1 hover:bg-gray-50 transition cursor-pointer min-w-0 max-w-full overflow-hidden"
                                                    title="Cliquer pour copier">
                                                    <span
                                                        class="text-gray-800 truncate">{{ $listing->user->discord_handle }}</span>
                                                    <span x-show="!copied" class="text-gray-400 shrink-0">📋</span>
                                                    <span x-show="copied" class="text-green-600 font-bold shrink-0">✓</span>
                                                </button>
                                            </div>
                                        @endif


                                    </div>

                                    <p class="mt-3 text-xs text-gray-500 italic">
                                        Conseil : Contactez ce vendeur pour convenir de la transaction.
                                    </p>
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