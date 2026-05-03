<?php

use Illuminate\Support\Facades\Route;

// Livewire
use App\Livewire\Dashboard;
use App\Livewire\Users\UserList;
use App\Livewire\ToolUsages\ToolUsageList;
use App\Livewire\IntegrationManager\IntegrationManager;
use App\Livewire\ServerAccount\Manager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//
// 🏠 トップ（home）
//
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

//
// 🔐 認証必須エリア
//
Route::middleware(['auth'])->group(function () {

    // ダッシュボード
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ユーザー管理
    Route::get('/users', UserList::class)->name('users');

    // 別名（メンバー）
    Route::get('/members', UserList::class)->name('members');

    // ツール利用状況
    Route::get('/tool-usages', ToolUsageList::class)->name('tool-usages');

    // 外部連携
    Route::get('/integrations', IntegrationManager::class)->name('integrations');

    // 🔥 サーバーアカウント管理（今回）
    Route::get('/server-accounts', Manager::class)->name('server-accounts');
});