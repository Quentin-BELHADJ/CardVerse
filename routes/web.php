<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\Admin\CollectionController as AdminCollectionController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\Admin\CardController as AdminCardController;
use Illuminate\Support\Facades\Route;

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

// Routes Admin
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('cards', AdminCardController::class)->except(['index', 'show']);
});

require __DIR__.'/auth.php';
