<?php

namespace App\Livewire\ToolUsages;

use App\Models\ToolUsage;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ToolUsageList extends Component
{
    use WithPagination;

    // 検索
    public $search = '';

    // フォーム
    public $user_id = '';
    public $tool_name = '';
    public $version = '';
    public $category = '';
    public $memo = '';
    public $editingId = null;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'tool_name' => 'required|string|max:255',
        'version' => 'nullable|string|max:100',
        'category' => 'nullable|string|max:100',
        'memo' => 'nullable|string',
    ];

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function getTotalToolsProperty()
    {
        return ToolUsage::count();
    }

    public function getUniqueUsersProperty()
    {
        return ToolUsage::distinct('user_id')->count('user_id');
    }

    // 一時的にコメントアウト（カラムが存在しないため）
    // public function getDevToolsProperty()
    // {
    //     return ToolUsage::where('category', 'development')->count();
    // }

    // public function getDesignToolsProperty()
    // {
    //     return ToolUsage::where('category', 'design')->count();
    // }

    public function resetInput()
    {
        $this->user_id = '';
        $this->tool_name = '';
        $this->version = '';
        $this->category = '';
        $this->memo = '';
        $this->editingId = null;
    }

    public function createToolUsage()
    {
        $this->validate();

        $data = [
            'user_id' => $this->user_id,
            'tool_name' => $this->tool_name,
            'version' => $this->version,
            'memo' => $this->memo,
        ];
        
        // categoryが存在する場合のみ追加
        if ($this->category) {
            $data['category'] = $this->category;
        }

        ToolUsage::create($data);

        session()->flash('success', 'ツールを追加しました');
        $this->resetInput();
    }

    public function editToolUsage($id)
    {
        $toolUsage = ToolUsage::findOrFail($id);
        $this->editingId = $id;
        $this->user_id = $toolUsage->user_id;
        $this->tool_name = $toolUsage->tool_name;
        $this->version = $toolUsage->version;
        $this->category = $toolUsage->category ?? '';
        $this->memo = $toolUsage->memo;
    }

    public function updateToolUsage()
    {
        $this->validate();

        $data = [
            'user_id' => $this->user_id,
            'tool_name' => $this->tool_name,
            'version' => $this->version,
            'memo' => $this->memo,
        ];
        
        if ($this->category) {
            $data['category'] = $this->category;
        }

        $toolUsage = ToolUsage::findOrFail($this->editingId);
        $toolUsage->update($data);

        session()->flash('success', 'ツールを更新しました');
        $this->resetInput();
    }

    public function deleteToolUsage($id)
    {
        ToolUsage::findOrFail($id)->delete();
        session()->flash('success', 'ツールを削除しました');
    }

    public function render()
    {
        $toolUsages = ToolUsage::with('user')
            ->when($this->search, function ($query) {
                $query->where('tool_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.tool-usages.tool-usage-list', [
            'toolUsages' => $toolUsages,
            'users' => $this->users,
            'totalTools' => $this->totalTools,
            'uniqueUsers' => $this->uniqueUsers,
            // 一時的に0を返す
            'devTools' => 0,
            'designTools' => 0,
        ]);
    }
}