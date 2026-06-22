<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\NewEmployee;
use App\Models\DeveloperAccount; // ★追加

class Dashboard extends Component
{
    // 既存のプロパティ
    public $totalUsers;
    public $retiredUsers;

    public $totalProducts;
    public $assignedProducts;
    public $unassignedProducts;

    public $newEmployees;
    public $scheduledEmployees;
    public $joinedEmployees;
    public $declinedEmployees;

    // ★追加：開発アカウント用のプロパティ
    public $totalDevAccounts;
    public $githubAccounts;
    public $dockerAccounts;
    public $redmineAccounts;
    public $svnAccounts;

    public function mount()
    {
        // ユーザー
        $this->totalUsers = User::count();

        $this->retiredUsers = User::where(
            'is_retired',
            true
        )->count();

        // PC
        $this->totalProducts = Product::count();

        $this->assignedProducts = Product::whereNotNull(
            'user_id'
        )->count();

        $this->unassignedProducts = Product::whereNull(
            'user_id'
        )->count();

        // 入社予定者
        $this->newEmployees = NewEmployee::count();

        $this->scheduledEmployees = NewEmployee::where(
            'status',
            '予定'
        )->count();

        $this->joinedEmployees = NewEmployee::where(
            'status',
            '入社済'
        )->count();

        $this->declinedEmployees = NewEmployee::where(
            'status',
            '辞退'
        )->count();

        // ★追加：開発アカウントの集計ロジック
        $this->totalDevAccounts = DeveloperAccount::count();
        
        $this->githubAccounts = DeveloperAccount::where(
            'tool_type', 
            'github'
        )->count();
        
        $this->dockerAccounts = DeveloperAccount::where(
            'tool_type', 
            'docker'
        )->count();
        
        $this->redmineAccounts = DeveloperAccount::where(
            'tool_type', 
            'redmine'
        )->count();
        
        $this->svnAccounts = DeveloperAccount::where(
            'tool_type', 
            'svn'
        )->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}