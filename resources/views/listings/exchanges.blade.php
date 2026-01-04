<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zone d\'Échange') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <div class="mb-6 bg-white p-4 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('listings.exchanges') }}" class="flex items-end gap-4">
                    <!-- Search -->
                    <div class="flex-grow min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Nom de la carte...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Filtrer par catégorie</label>
                        <select name="category" class="mt-1 block w-48 border-gray-300 rounded-md shadow-sm">
                            <option value="">Toutes</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filtrer</button>
                    @if(request('category') || request('search'))
                        <a href="{{ route('listings.exchanges') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                    @endif
                </form>
            </div>

            @if($listings->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Aucune carte disponible à l'échange pour le moment. Soyez le premier !
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col h-full relative group">

                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $listing->card->name }}</h3>
                                    <span class="text-sm text-gray-500">{{ $listing->card->rarity ?? 'Carte' }}</span>
                                </div>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-semibold">
                                    {{ $listing->condition->value }}
                                </span>
                            </div>

                            @if($listing->card->image_url)
                                <div class="h-48 w-full bg-gray-100 rounded mb-4 overflow-hidden flex items-center justify-center">
                                    <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}"
                                        class="h-full object-contain">
                                </div>
                            @endif

                            <div class="mb-4 flex-grow">
                                <p class="text-sm font-semibold text-gray-700">Recherche :</p>
                                @if($listing->targetCards->isNotEmpty())
                                    <div class="mt-2 space-y-2">
                                        @foreach($listing->targetCards as $target)
                                            <div class="flex items-center gap-2 bg-gray-50 p-1.5 rounded border border-gray-100">
                                                @if($target->image_url)
                                                    <img src="{{ $target->image_url }}" alt="{{ $target->name }}" 
                                                         class="w-8 h-auto object-contain rounded border border-gray-200"
                                                         title="{{ $target->name }}">
                                                @else
                                                    <div class="w-8 h-10 bg-gray-200 flex items-center justify-center rounded text-xs text-gray-400">?</div>
                                                @endif
                                                <span class="text-xs text-gray-700 font-medium truncate">{{ $target->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Ouvert à toute proposition</p>
                                @endif
                            </div>

                            <hr class="my-3 border-gray-100">

                            <div x-data="{ showContact: false }">
                                <div class="flex justify-between items-center">
                                    <div class="text-sm">
                                        <span class="text-gray-500">Proposé par :</span>
                                        <span class="font-medium">{{ $listing->user->name }}</span>
                                    </div>

                                    @if(auth()->id() !== $listing->user->id)
                                        <button @click="showContact = !showContact"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold focus:outline-none">
                                            <span x-show="!showContact">Contacter</span>
                                            <span x-show="showContact">Fermer</span>
                                        </button>
                                    @endif
                                </div>

                                <div x-show="showContact"
                                    class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded text-sm text-indigo-900 transition-all"
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
                                                    <span class="text-gray-800 truncate">{{ $listing->user->discord_handle }}</span>
                                                    <span x-show="!copied" class="text-gray-400 shrink-0">📋</span>
                                                    <span x-show="copied" class="text-green-600 font-bold shrink-0">✓</span>
                                                </button>
                                            </div>
                                        @endif


                                    </div>

                                    <p class="mt-3 text-xs text-gray-500 italic">
                                        Conseil : Contactez ce collectionneur pour convenir des modalités de l'échange.
                                    </p>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>