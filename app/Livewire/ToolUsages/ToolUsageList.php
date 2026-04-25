<?php

namespace App\Livewire\ToolUsages;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\ToolUsage;
use App\Models\User;

class ToolUsageList extends Component
{
    use WithPagination;

    public $search = '';
    public $user_id = '';
    public $tool_name = '';
    public $version = '';
    public $memo = '';
    public $editingId = null;

    protected $rules = [
        'user_id'   => 'required|exists:users,id',
        'tool_name' => 'required|string|max:255',
        'version'   => 'nullable|string|max:100',
        'memo'      => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
    }

    public function createToolUsage()
    {
        $this->validate();

        ToolUsage::create([
            'user_id'   => $this->user_id,
            'tool_name' => $this->tool_name,
            'version'   => $this->version,
            'memo'      => $this->memo,
        ]);

        $this->resetInput();
    }

    public function editToolUsage($id)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $toolUsage       = ToolUsage::findOrFail($id);
        $this->editingId = $toolUsage->id;
        $this->user_id   = $toolUsage->user_id;
        $this->tool_name = $toolUsage->tool_name;
        $this->version   = $toolUsage->version;
        $this->memo      = $toolUsage->memo;
    }

    public function updateToolUsage()
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        $this->validate();

        ToolUsage::findOrFail($this->editingId)->update([
            'user_id'   => $this->user_id,
            'tool_name' => $this->tool_name,
            'version'   => $this->version,
            'memo'      => $this->memo,
        ]);

        $this->resetInput();
    }

    public function deleteToolUsage($id)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);

        try {
            ToolUsage::findOrFail($id)->delete();
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => '削除に失敗しました']);
        }
    }

    public function resetInput()
    {
        $this->user_id   = '';
        $this->tool_name = '';
        $this->version   = '';
        $this->memo      = '';
        $this->editingId = null;
    }

    public function render()
    {
        $search = $this->search;

        $toolUsages = ToolUsage::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tool_name', 'like', '%' . $search . '%')
                      ->orWhere('version', 'like', '%' . $search . '%')
                      ->orWhere('memo', 'like', '%' . $search . '%')
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', '%' . $search . '%');
                      });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $users = User::orderBy('name')->get();

        return view('livewire.tool-usages.tool-usage-list', compact('toolUsages', 'users'))
            ->layout('layouts.admin');
    }
}