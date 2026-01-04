<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs (sauf soi-même).
     * Permet à l'administrateur de voir les membres inscrits.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get(); // On ne se liste pas soi-même dans l'admin
        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de modification d'un utilisateur.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Met à jour les informations d'un utilisateur (Rôle, Pseudo Discord).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,admin',
            'discord_handle' => 'nullable|string|max:255',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Bascule l'état de bannissement d'un utilisateur (Ban/Unban).
     * Inverse la valeur booléenne de 'is_banned'.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleBan(User $user)
    {
        // On inverse la valeur booléenne
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banni' : 'réhabilité';

        return back()->with('success', "L'utilisateur {$user->name} a été {$status}.");
    }
}
