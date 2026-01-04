<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier l\'utilisateur : ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow sm:rounded-lg">

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('Nom')" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-50 shadow-none cursor-not-allowed"
                            type="text" :value="$user->name" disabled />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-50 shadow-none cursor-not-allowed"
                            type="email" :value="$user->email" disabled />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Rôle du membre')" />
                        <select name="role" id="role"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            <option value="member" @selected(old('role', $user->role) == 'member')>Membre (Standard)
                            </option>
                            <option value="admin" @selected(old('role', $user->role) == 'admin')>Administrateur</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="discord_handle" :value="__('Pseudo Discord')" />
                        <x-text-input id="discord_handle" name="discord_handle" type="text" class="block mt-1 w-full"
                            :value="old('discord_handle', $user->discord_handle)"
                            placeholder="Ex: Pseudo#1234 ou pseudo" />
                        <x-input-error :messages="$errors->get('discord_handle')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="text-sm text-gray-600 underline hover:text-gray-900">
                            {{ __('Annuler') }}
                        </a>
                        <x-primary-button>
                            {{ __('Mettre à jour l\'utilisateur') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>