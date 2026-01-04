<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil de {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- User Info -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Contact</h3>
                <p class="mt-2 text-gray-600">Email: <a href="mailto:{{ $user->email }}"
                        class="text-indigo-600 hover:underline">{{ $user->email }}</a></p>
                <p class="text-sm text-gray-500 mt-1">Membre depuis le {{ $user->created_at->format('d/m/Y') }}</p>
            </div>

            <!-- User's Listings -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <header class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Cartes en vente ({{ $listings->count() }})</h3>
                </header>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($listings as $listing)
                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition">
                            <div class="h-40 bg-gray-200 flex items-center justify-center">
                                @if($listing->card->image_url)
                                    <img src="{{ $listing->card->image_url }}" alt="{{ $listing->card->name }}"
                                        class="object-contain w-full h-full">
                                @else
                                    <span class="text-xs text-gray-500">No Image</span>
                                @endif
                            </div>
                            <div class="p-3">
                                <h4 class="font-bold text-gray-800">{{ $listing->card->name }}</h4>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-indigo-600 font-bold">{{ $listing->price }} €</span>
                                    <span
                                        class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $listing->condition->value }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-full">Cet utilisateur n'a aucune carte en vente pas ce moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>