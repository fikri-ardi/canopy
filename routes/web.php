<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Livewire\Budget;
use App\Livewire\Dashboard;
use App\Livewire\Investment;
use App\Livewire\Labels;
use App\Livewire\Platforms;
use App\Livewire\Spends;
use App\Livewire\Statuses;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', ['google', 'facebook', 'github'])
        ->name('social.redirect');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', ['google', 'facebook', 'github'])
        ->name('social.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/budgets', Budget::class)->name('budgets');
    Route::get('/spends', Spends::class)->name('spends');
    Route::get('/investment', Investment::class)->name('investment');
    Route::redirect('/reports', '/')->name('reports');
    Route::get('/labels', Labels::class)->name('labels');
    Route::get('/platforms', Platforms::class)->name('platforms');
    Route::get('/statuses', Statuses::class)->name('statuses');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
