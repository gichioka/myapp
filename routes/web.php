<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\NewEmployeeManager\NewEmployeeManager;
use App\Livewire\Dashboard;
use App\Livewire\Users\UserList;
use App\Livewire\ToolUsages\ToolUsageList;
use App\Livewire\IntegrationManager\IntegrationManager;
use App\Livewire\ServerAccount\Manager;
use App\Livewire\Product\ProductManager;  // ← これがPC在庫管理

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserList::class)->name('users');
    Route::get('/members', UserList::class)->name('members');
    Route::get('/tool-usages', ToolUsageList::class)->name('tool-usages');
    Route::get('/integrations', IntegrationManager::class)->name('integrations');
    Route::get('/server-accounts', Manager::class)->name('server-accounts');
    Route::get('/products', ProductManager::class)->name('products');  // ← ここ！
    Route::get('/new-employees', NewEmployeeManager::class)->name('new-employees');
});