<?php

use App\Livewire\Budget;
use App\Livewire\Dashboard;
use App\Livewire\Labels;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/budgets', Budget::class);
Route::get('/labels', Labels::class);
