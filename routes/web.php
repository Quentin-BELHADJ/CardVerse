<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\Admin\CardController as AdminCardController;
use Illuminate\Support\Facades\Route;

// À ajouter en haut de routes/web.php
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    Route::get('/check-ban', function () {
        if (auth()->user()->is_banned) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre compte a été suspendu.']);
        }
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion de ma collection
    Route::resource('listings', App\Http\Controllers\ListingController::class)->only(['index', 'store', 'update', 'destroy']);
});

// 1. ROUTES PUBLIQUES (Visualisation pour tous)
// Elles utilisent le contrôleur public qui renvoie les vues communes
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');
Route::get('/marketplace', [App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace.index');

// 2. ROUTES ADMIN (Actions de modification uniquement)
// On garde le préfixe admin pour les actions de création/édition
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {

    // Pour les collections : on ne garde que les routes de traitement
    Route::get('collections/import', [AdminCollectionController::class, 'import'])->name('collections.import');
    Route::post('collections/import', [AdminCollectionController::class, 'storeImport'])->name('collections.storeImport');
    Route::resource('collections', AdminCollectionController::class)->except(['index', 'show']);

    // Pour les cartes : idem
    Route::resource('cards', AdminCardController::class)->except(['index', 'show']);
});


// Routes publiques
Route::get('/profile/{user}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('users.show');
Route::get('/cards/search', [CardController::class, 'search'])->name('cards.search');
Route::resource('cards', CardController::class)->only(['show']);

// Routes Admin
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('collections', AdminCollectionController::class);
    Route::resource('cards', AdminCardController::class);

    // CRUD Utilisateurs + Route de Bannissement
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-ban', [App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.toggle-ban');
});


require __DIR__ . '/auth.php';
