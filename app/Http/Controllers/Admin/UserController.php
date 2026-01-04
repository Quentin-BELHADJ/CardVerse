<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get(); // On ne se liste pas soi-même
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,admin',
            'discord_handle' => 'nullable|string|max:255',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour.');
    }

    public function toggleBan(User $user)
    {
        // On inverse la valeur booléenne
        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banni' : 'réhabilité';

        return back()->with('success', "L'utilisateur {$user->name} a été {$status}.");
    }
}
