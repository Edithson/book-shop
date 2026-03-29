<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\WebhookController;
use App\Livewire\Front\Storefront;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\BookController;

// Route::view('/', 'welcome');

Route::get('/', HomeController::class . '@index')->name('home');
Route::get('/admin', Dashboard::class . '@index')->name('admin.dashboard');
Route::get('/admin/books', [BookController::class, 'index_admin'])->name('admin.books.index');
Route::get('/admin/books/create', [BookController::class, 'create'])->name('admin.books.create');
Route::post('/admin/books/store', [BookController::class, 'store'])->name('admin.books.store');
Route::get('/admin/books/{book}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
Route::delete('/admin/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');

Route::post('/webhook/notchpay', [WebhookController::class, 'handleNotchPay'])->name('webhook.notchpay');

Route::view('/admin/dashboard', 'admin.pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::view('/admin/profile', 'admin.pages.profile')
    ->middleware(['auth'])
    ->name('admin.profile');

// Redirige l'utilisateur vers Google ou Facebook
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');

// Gère le retour de Google ou Facebook après que l'utilisateur ait cliqué sur "Accepter"
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

require __DIR__.'/auth.php';
