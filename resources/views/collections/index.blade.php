<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight italic">
                {{ __('Catalogue CardVerse') }}
            </h2>

            @can('admin-access')
                <a href="{{ route('admin.collections.create') }}">
                    <x-primary-button>
                        + Nouvelle Collection
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($collections as $collection)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex items-center justify-between group border border-gray-100 hover:shadow-md transition">

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
