<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;
use App\Models\NewEmployee;

class Dashboard extends Component
{
    public $totalUsers;
    public $retiredUsers;

    public $totalProducts;
    public $assignedProducts;
    public $unassignedProducts;

    public $newEmployees;
    public $scheduledEmployees;
    public $joinedEmployees;
    public $declinedEmployees;

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
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}