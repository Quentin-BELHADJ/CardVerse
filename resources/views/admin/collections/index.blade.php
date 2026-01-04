<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Collections') }}
            <div class="space-x-2">
                <a href="{{ route('admin.collections.import') }}"
                    class="bg-green-500 text-sm text-white px-4 py-2 rounded">Importer JSON</a>
                <a href="{{ route('admin.collections.create') }}"
                    class="bg-blue-500 text-sm text-white px-4 py-2 rounded">+ Ajouter</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Nom</th>
                            <th class="p-2">Catégorie</th>
                            <th class="p-2">Date</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collections as $collection)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-2">{{ $collection->name }}</td>
                                <td class="p-2">{{ $collection->category }}</td>
                                <td class="p-2">{{ $collection->release_date }}</td>
                                <td class="p-2">
                                    <a href="#" class="text-blue-600">Editer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>