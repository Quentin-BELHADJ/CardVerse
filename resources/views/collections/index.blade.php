<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('Catalogue CardVerse') }}
            </h2>

            @can('admin-access')
                <div class="space-x-2 flex items-center">
                    <a href="{{ route('admin.collections.import') }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Importer JSON
                    </a>
                    <a href="{{ route('admin.collections.create') }}">
                        <x-primary-button>
                            + Nouvelle Collection
                        </x-primary-button>
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <div class="mb-6 bg-white p-4 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('collections.index') }}" class="flex flex-wrap items-end gap-4">

                    <!-- Search -->
                    <div class="flex-grow min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Nom de la collection...">
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

                    <!-- Date Start -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Début / Exacte</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <!-- Date End -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Fin</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filtrer</button>
                        @if(request()->anyFilled(['search', 'category', 'start_date', 'end_date']))
                            <a href="{{ route('collections.index') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($collections as $collection)
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex items-center justify-between group border border-gray-100 hover:shadow-md transition">

                        <a href="{{ route('collections.show', $collection) }}" class="flex-grow">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full">
                                    {{ $collection->category }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">{{ $collection->name }}</h3>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 italic">
                                Sortie le : {{ \Carbon\Carbon::parse($collection->release_date)->format('d/m/Y') }}
                            </p>
                        </a>

                        @can('admin-access')
                            <div class="flex flex-col space-y-1 ml-4 border-l pl-4 border-gray-100">
                                <a href="{{ route('admin.collections.edit', $collection) }}">
                                    <x-secondary-button class="!py-1 !px-2 !text-[10px] w-full justify-center">
                                        {{ __('Modifier') }}
                                    </x-secondary-button>
                                </a>

                                <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST"
                                    onsubmit="return confirm('Supprimer définitivement cette collection ?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit" class="!py-1 !px-2 !text-[10px] w-full justify-center">
                                        {{ __('Supprimer') }}
                                    </x-danger-button>
                                </form>
                            </div>
                        @endcan
                    </div>
                @endforeach
            </div>

            @if($collections->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    Aucune collection n'est disponible pour le moment.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>