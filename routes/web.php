<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\Admin\CardController as AdminCardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ListingController;
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
});

/* --------------------------------------------------------------------------
   GESTION PROFIL & LISTINGS (Rôle C - Authentifié)
   --------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profil (Déjà présent via Breeze, c'est ici que vous gérerez le contact_info)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GESTION DES LISTINGS (Développeur C) ---
    
    // 1. Création (DOIT être avant /{listing} pour ne pas être confondu avec un ID)
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');

    // 2. Modification & Suppression (Sécurisées par des Gates dans le contrôleur)
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::patch('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
});

// 1. ROUTES PUBLIQUES (Visualisation pour tous)
// Elles utilisent le contrôleur public qui renvoie les vues communes
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');

// 2. ROUTES ADMIN (Actions de modification uniquement)
// On garde le préfixe admin pour les actions de création/édition
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {

    // Pour les collections : on ne garde que les routes de traitement
    Route::resource('collections', AdminCollectionController::class)->except(['index', 'show']);

    // Pour les cartes : idem
    Route::resource('cards', AdminCardController::class)->except(['index', 'show']);
});


// Routes publiques
Route::resource('cards', CardController::class)->only(['show']);
Route::get('/exchanges', [ListingController::class, 'indexExchanges'])->name('listings.exchanges');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Routes Admin
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('collections', AdminCollectionController::class);
    Route::resource('cards', AdminCardController::class);

    // CRUD Utilisateurs + Route de Bannissement
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/toggle-ban', [App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.toggle-ban');
});


require __DIR__ . '/auth.php';
