<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestion des Utilisateurs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b text-gray-400 text-sm uppercase">
                        <th class="p-3">Utilisateur</th>
                        <th class="p-3">Rôle</th>
                        <th class="p-3">Statut</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <div class="font-bold">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="p-3">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ strtoupper($user->role) }}
                                    </span>
                            </td>
                            <td class="p-3">
                                @if($user->is_banned)
                                    <span class="text-red-600 text-xs font-bold italic">Banni</span>
                                @else
                                    <span class="text-green-600 text-xs font-bold">Actif</span>
                                @endif
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-indigo-600 hover:underline text-sm">Modifier</a>

                                <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST"
                                      class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-sm {{ $user->is_banned ? 'text-green-600' : 'text-red-600' }} hover:underline">
                                        {{ $user->is_banned ? 'Débannir' : 'Bannir' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
