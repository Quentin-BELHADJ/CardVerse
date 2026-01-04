<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Cards -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Mes Cartes</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalCards }}</div>
                    </div>
                </div>

                <!-- For Sale -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">En Vente</div>
                        <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $cardsForSale }}</div>
                    </div>
                </div>

                <!-- For Trade -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">En Échange</div>
                        <div class="mt-2 text-3xl font-bold text-purple-600">{{ $cardsForTrade }}</div>
                    </div>
                </div>

                <!-- Collections -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Collections</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">{{ $collectionsCount }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-2">Bienvenue sur CardVerse !</h3>
                    <p class="text-gray-600">Gérez votre collection, vendez vos doublons et échangez avec la communauté.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>