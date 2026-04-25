<?php
 
use App\Livewire\Dashboard;
use App\Livewire\Users\UserList;
use App\Livewire\ToolUsages\ToolUsageList;
use App\Livewire\IntegrationManager\IntegrationManager;
use Illuminate\Support\Facades\Route;
 
// トップページをdashboardにリダイレクト
Route::get('/', function () {
    return redirect()->route('dashboard');
});
 
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',    Dashboard::class)->name('dashboard');
    Route::get('/users',        UserList::class)->name('users');
    Route::get('/members',      UserList::class)->name('members');
    Route::get('/tool-usages',  ToolUsageList::class)->name('tool-usages');
    Route::get('/integrations', IntegrationManager::class)->name('integrations');
});
 