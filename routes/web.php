<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Redirige l'utilisateur vers Google ou Facebook
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');

// Gère le retour de Google ou Facebook après que l'utilisateur ait cliqué sur "Accepter"
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

require __DIR__.'/auth.php';
