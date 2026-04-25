<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Dashboard extends Component
{
    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalUsers' => User::count(),
            'retiredUsers' => User::where('is_retired', true)->count(),
        ])->layout('layouts.admin');
    }
}