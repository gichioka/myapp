<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $email, $password, $department = '', $employment_type = '正社員', $is_retired = false, $comment = '';
    public $editingUserId = null;

    protected $rules = [
        'name'            => 'required|string|max:255',
        'email'           => 'required|email|max:255|unique:users,email',
        'password'        => 'required|min:6',
        'department'      => 'nullable|string|max:255',
        'employment_type' => 'required|string|max:20',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
    }

    public function createUser()
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $this->validate();

        User::create([
            'name'            => $this->name,
            'email'           => $this->email,
            'password'        => bcrypt($this->password),
            'department'      => $this->department,
            'employment_type' => $this->employment_type,
            'is_retired'      => $this->is_retired,
            'comment'         => $this->comment,
        ]);

        $this->resetInput();
    }

    public function editUser($id)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $user = User::findOrFail($id);
        $this->editingUserId   = $user->id;
        $this->name            = $user->name;
        $this->email           = $user->email;
        $this->department      = $user->department;
        $this->employment_type = $user->employment_type;
        $this->is_retired      = $user->is_retired;
        $this->comment         = $user->comment;
    }

    public function updateUser()
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $this->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'department'      => 'nullable|string|max:255',
            'employment_type' => 'required|string|max:20',
        ]);

        $user = User::findOrFail($this->editingUserId);
        $user->update([
            'name'            => $this->name,
            'email'           => $this->email,
            'department'      => $this->department,
            'employment_type' => $this->employment_type,
            'is_retired'      => $this->is_retired,
            'comment'         => $this->comment,
        ]);

        $this->resetInput();
    }

    public function deleteUser($id)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        try {
            User::findOrFail($id)->delete();
            $this->dispatch('user-deleted');
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'ユーザーの削除に失敗しました']);
        }
    }

    public function resetInput()
    {
        $this->name            = '';
        $this->email           = '';
        $this->password        = '';
        $this->department      = '';
        $this->employment_type = '正社員';
        $this->is_retired      = false;
        $this->comment         = '';
        $this->editingUserId   = null;
    }

    public function render()
    {
        $search = $this->search;

        $users = User::query()
            ->where('id', '!=', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('department', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.users.user-list', compact('users'))
            ->layout('layouts.admin');
    }
}