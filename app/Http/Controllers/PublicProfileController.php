<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\ListingStatus;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        if ($user->is_banned) {
            abort(404);
        }
        // Load user's listings that are FOR SALE only
        $listings = $user->listings()
            ->where('status', ListingStatus::FOR_SALE)
            ->with('card')
            ->latest()
            ->get();

        return view('users.show', compact('user', 'listings'));
    }
}
