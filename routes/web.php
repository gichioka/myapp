<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\NewEmployeeManager\NewEmployeeManager;
// Livewire
use App\Livewire\Dashboard;
use App\Livewire\Users\UserList;
use App\Livewire\ToolUsages\ToolUsageList;
use App\Livewire\IntegrationManager\IntegrationManager;
use App\Livewire\ServerAccount\Manager;
use App\Livewire\Product\ProductManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {

    // ダッシュボード
    Route::get('/dashboard', Dashboard::class)
        ->name('dashboard');

    // ユーザー管理
    Route::get('/users', UserList::class)
        ->name('users');

    // メンバー管理
    Route::get('/members', UserList::class)
        ->name('members');

    // ツール利用状況
    Route::get('/tool-usages', ToolUsageList::class)
        ->name('tool-usages');

    // 外部連携管理
    Route::get('/integrations', IntegrationManager::class)
        ->name('integrations');

    // サーバーアカウント管理
    Route::get('/server-accounts', Manager::class)
        ->name('server-accounts');

    // PC在庫管理
    Route::get('/products', ProductManager::class)
        ->name('products');

    // 新規従業員管理
    Route::get('/new-employees', NewEmployeeManager::class)
        ->name('new-employees');
});
