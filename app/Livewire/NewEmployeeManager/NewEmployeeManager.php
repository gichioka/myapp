<?php

namespace App\Livewire\NewEmployeeManager;

use App\Models\NewEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class NewEmployeeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterDepartment = '';
    
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;
    
    public $name = '';
    public $applicant_name = '';
    public $email = '';
    public $department = '';
    public $join_date = '';
    public $status = '予定';
    public $remarks = '';
    
    public $needs_github = false;
    public $needs_redmine = false;
    public $needs_svn = false;
    public $needs_google_drive = false;
    public $needs_unity = false;
    public $needs_maya = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'applicant_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'department' => 'nullable|string|max:255',
        'join_date' => 'required|date',
        'status' => 'required|string',
        'remarks' => 'nullable|string',
    ];

    // 重要な修正: livewire v3ではupdatedプロパティ名
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterDepartment()
    {
        $this->resetPage();
    }

    public function getStatsProperty()
    {
        return [
            '予定' => NewEmployee::where('status', '予定')->count(),
            '入社済' => NewEmployee::where('status', '入社済')->count(),
            '辞退' => NewEmployee::where('status', '辞退')->count(),
        ];
    }

    public function getDepartmentsProperty()
    {
        return NewEmployee::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->toArray();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $emp = NewEmployee::findOrFail($id);
        $this->editingId = $id;
        $this->name = $emp->name;
        $this->applicant_name = $emp->applicant_name;
        $this->email = $emp->email;
        $this->department = $emp->department;
        $this->join_date = $emp->join_date->format('Y-m-d');
        $this->status = $emp->status;
        $this->remarks = $emp->remarks;
        
        $this->needs_github = (bool)$emp->needs_github;
        $this->needs_redmine = (bool)$emp->needs_redmine;
        $this->needs_svn = (bool)$emp->needs_svn;
        $this->needs_google_drive = (bool)$emp->needs_google_drive;
        $this->needs_unity = (bool)$emp->needs_unity;
        $this->needs_maya = (bool)$emp->needs_maya;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'applicant_name' => $this->applicant_name,
            'email' => $this->email,
            'department' => $this->department,
            'join_date' => $this->join_date,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'needs_github' => $this->needs_github,
            'needs_redmine' => $this->needs_redmine,
            'needs_svn' => $this->needs_svn,
            'needs_google_drive' => $this->needs_google_drive,
            'needs_unity' => $this->needs_unity,
            'needs_maya' => $this->needs_maya,
        ];

        if ($this->isEditing) {
            NewEmployee::findOrFail($this->editingId)->update($data);
            session()->flash('success', '更新しました');
        } else {
            NewEmployee::create($data);
            session()->flash('success', '登録しました');
        }

        $this->closeModal();
    }

    public function updateStatus($id, $status)
    {
        NewEmployee::findOrFail($id)->update(['status' => $status]);
        session()->flash('success', 'ステータスを更新しました');
    }

    public function delete($id)
    {
        NewEmployee::findOrFail($id)->delete();
        session()->flash('success', '削除しました');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterDepartment = '';
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->applicant_name = '';
        $this->email = '';
        $this->department = '';
        $this->join_date = '';
        $this->status = '予定';
        $this->remarks = '';
        $this->needs_github = false;
        $this->needs_redmine = false;
        $this->needs_svn = false;
        $this->needs_google_drive = false;
        $this->needs_unity = false;
        $this->needs_maya = false;
    }

    public function render()
    {
        $query = NewEmployee::query();
        
        // 検索条件（デバッグしやすいように修正）
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('applicant_name', 'like', $searchTerm)
                  ->orWhere('department', 'like', $searchTerm);
            });
        }
        
        // ステータスフィルター
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }
        
        // 部署フィルター
        if (!empty($this->filterDepartment)) {
            $query->where('department', $this->filterDepartment);
        }
        
        $employees = $query->orderBy('join_date', 'asc')->paginate(15);

        return view('livewire.new-employee-manager.new-employee-manager', [
            'employees' => $employees,
            'stats' => $this->stats,
            'departments' => $this->departments,
        ]);
    }
}