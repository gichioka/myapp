<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\NewEmployeeManager\NewEmployeeManager;
use App\Livewire\Dashboard;
use App\Livewire\Retirements\RetirementManager;
use App\Livewire\Users\UserList;
use App\Livewire\ToolUsages\ToolUsageList;
use App\Livewire\IntegrationManager\IntegrationManager;
use App\Livewire\ServerAccount\Manager;
use App\Livewire\Product\ProductManager;  // ← これがPC在庫管理
// 【追加】新しく作ったフォルダ階層のLivewireクラスをインポート
use App\Livewire\DeveloperAccountsManager\DeveloperAccountsManager;

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
    
    // 【追加】開発アカウント管理用のルーティング
    Route::get('/developer-accounts', DeveloperAccountsManager::class)->name('developer-accounts');

    Route::get('/new-employees', NewEmployeeManager::class)->name('new-employees');
    Route::get('/retirements', RetirementManager::class)->name('retirements');
});