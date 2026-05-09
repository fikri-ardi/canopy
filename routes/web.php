<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Livewire\Budget;
use App\Livewire\Dashboard;
use App\Livewire\Labels;
use App\Livewire\Reports;
use App\Livewire\Spends;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/budgets', Budget::class)->name('budgets');
    Route::get('/spends', Spends::class)->name('spends');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/labels', Labels::class)->name('labels');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
