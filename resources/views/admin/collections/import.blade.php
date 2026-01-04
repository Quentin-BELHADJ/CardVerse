<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Importer une Collection (JSON)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.collections.storeImport') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fichier JSON</label>
                        <input type="file" name="json_file" accept=".json"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-xs text-gray-500 mt-1">Le fichier doit contenir: name, category, release_date et
                            un tableau cards.</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <x-primary-button>
                            {{ __('Importer') }}
                        </x-primary-button>
                        <a href="{{ route('collections.index') }}"
                            class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                            Annuler
                        </a>
                    </div>
                </form>

                <div class="mt-8 p-4 bg-gray-100 rounded text-sm">
                    <p class="font-bold">Exemple de format :</p>
                    <pre class="mt-2 bg-gray-800 text-white p-2 rounded overflow-x-auto">
{
  "name": "Pokémon 151",
  "category": "Pokémon",
  "release_date": "2023-09-22",
  "cards": [
    {
      "name": "Pikachu",
      "rarity": "Illustration Rare",
      "image_url": "https://..."
    }
  ]
}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>