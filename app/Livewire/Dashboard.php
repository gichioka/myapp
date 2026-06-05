<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Product;

class Dashboard extends Component
{
    public $totalUsers;
    public $retiredUsers;

    public $totalProducts;
    public $assignedProducts;
    public $unassignedProducts;

    public function mount()
    {
        $this->totalUsers = User::count();

        $this->retiredUsers = User::where(
            'is_retired',
            true
        )->count();

        $this->totalProducts = Product::count();

        $this->assignedProducts = Product::whereNotNull(
            'user_id'
        )->count();

        $this->unassignedProducts = Product::whereNull(
            'user_id'
        )->count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}