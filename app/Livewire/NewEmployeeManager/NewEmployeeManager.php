<?php

namespace App\Livewire\NewEmployeeManager;

use App\Models\NewEmployee;
use Livewire\Component;
use Livewire\WithPagination;

class NewEmployeeManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterDepartment = '';

    public bool $showModal = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    public string $applicant_name = '';
    public string $name = '';
    public string $email = '';
    public string $department = '';
    public string $join_date = '';
    public string $status = '予定';
    public bool $needs_github = false;
    public bool $needs_redmine = false;
    public bool $needs_svn = false;
    public bool $needs_google_drive = false;
    public bool $needs_unity = false;
    public bool $needs_maya = false;
    public string $remarks = '';

    protected function rules(): array
    {
        return [
            'applicant_name' => 'required|string|max:255',
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'department'     => 'nullable|string|max:255',
            'join_date'      => 'required|date',
            'status'         => 'required|in:予定,入社済,辞退',
            'remarks'        => 'nullable|string',
        ];
    }

    protected $queryString = [
        'search'           => ['except' => ''],
        'filterStatus'     => ['except' => ''],
        'filterDepartment' => ['except' => ''],
    ];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterDepartment(): void { $this->resetPage(); }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $employee = NewEmployee::findOrFail($id);
        $this->editingId          = $id;
        $this->isEditing          = true;
        $this->applicant_name     = $employee->applicant_name;
        $this->name               = $employee->name;
        $this->email              = $employee->email ?? '';
        $this->department         = $employee->department ?? '';
        $this->join_date          = $employee->join_date->format('Y-m-d');
        $this->status             = $employee->status;
        $this->needs_github       = $employee->needs_github;
        $this->needs_redmine      = $employee->needs_redmine;
        $this->needs_svn          = $employee->needs_svn;
        $this->needs_google_drive = $employee->needs_google_drive;
        $this->needs_unity        = $employee->needs_unity;
        $this->needs_maya         = $employee->needs_maya;
        $this->remarks            = $employee->remarks ?? '';
        $this->showModal          = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = array_merge($validated, [
            'needs_github'       => $this->needs_github,
            'needs_redmine'      => $this->needs_redmine,
            'needs_svn'          => $this->needs_svn,
            'needs_google_drive' => $this->needs_google_drive,
            'needs_unity'        => $this->needs_unity,
            'needs_maya'         => $this->needs_maya,
        ]);

        if ($this->isEditing) {
            NewEmployee::findOrFail($this->editingId)->update($data);
        } else {
            NewEmployee::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $this->isEditing ? '更新しました。' : '登録しました。');
    }

    public function delete(int $id): void
    {
        NewEmployee::findOrFail($id)->delete();
        session()->flash('success', '削除しました。');
    }

    public function updateStatus(int $id, string $status): void
    {
        NewEmployee::findOrFail($id)->update(['status' => $status]);
    }

    private function resetForm(): void
    {
        $this->editingId          = null;
        $this->applicant_name     = '';
        $this->name               = '';
        $this->email              = '';
        $this->department         = '';
        $this->join_date          = '';
        $this->status             = '予定';
        $this->needs_github       = false;
        $this->needs_redmine      = false;
        $this->needs_svn          = false;
        $this->needs_google_drive = false;
        $this->needs_unity        = false;
        $this->needs_maya         = false;
        $this->remarks            = '';
        $this->resetValidation();
    }

    public function render()
    {
        $employees = NewEmployee::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('applicant_name', 'like', "%{$this->search}%")
                  ->orWhere('department', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn($q) =>
                $q->where('status', $this->filterStatus)
            )
            ->when($this->filterDepartment, fn($q) =>
                $q->where('department', $this->filterDepartment)
            )
            ->orderBy('join_date')
            ->paginate(20);

        $departments = NewEmployee::select('department')
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        $stats = [
            '予定'   => NewEmployee::where('status', '予定')->count(),
            '入社済' => NewEmployee::where('status', '入社済')->count(),
            '辞退'   => NewEmployee::where('status', '辞退')->count(),
        ];

        return view('livewire.new-employee-manager.new-employee-manager', compact('employees', 'departments', 'stats'));
    }
}