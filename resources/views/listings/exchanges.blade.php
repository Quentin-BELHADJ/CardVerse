<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Zone d\'Échange') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                                    <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}" class="h-full object-contain">
                                </div>
                            @endif

                            <div class="mb-4 flex-grow">
                                <p class="text-sm font-semibold text-gray-700">Recherche :</p>
                                @if($listing->targetCards->isNotEmpty())
                                    <ul class="list-disc list-inside text-sm text-gray-600 mt-1">
                                        @foreach($listing->targetCards as $target)
                                            <li>{{ $target->name }}</li>
                                        @endforeach
                                    </ul>
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
                                    
                                    <button @click="showContact = !showContact" 
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold focus:outline-none">
                                        <span x-show="!showContact">Contacter</span>
                                        <span x-show="showContact">Fermer</span>
                                    </button>
                                </div>

                                <div x-show="showContact" 
                                    class="mt-3 p-3 bg-indigo-50 border border-indigo-100 rounded text-sm text-indigo-900 transition-all"
                                    style="display: none;">
                                    
                                    <p class="font-bold mb-2 border-b border-indigo-200 pb-1">Moyens de contact :</p>

                                    <div class="space-y-2">
                                        @if($listing->user->discord_handle)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold w-16">Discord:</span>
                                                <span class="bg-white px-2 py-0.5 rounded border border-indigo-200 font-mono select-all">
                                                    {{ $listing->user->discord_handle }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold w-16">Email:</span>
                                            <a href="mailto:{{ $listing->user->email }}" class="text-indigo-600 underline hover:text-indigo-800">
                                                {{ $listing->user->email }}
                                            </a>
                                        </div>
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