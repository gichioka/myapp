<?php

namespace App\Livewire\Retirements;

use App\Models\Retirement;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class RetirementManager extends Component
{
    use WithPagination;

    // 一覧フィルタ用
    public string $statusFilter = '';
    public string $pcReturnStatusFilter = '';

    // モーダル制御
    public bool $showModal = false;
    public bool $isEditMode = false;

    // 編集/作成対象
    public ?int $retirementId = null;
    public ?int $user_id = null;
    public string $retired_at = '';
    public string $used_pc_info = '';
    public string $status = 'pending';
    public string $pc_return_status = 'unreturned';
    public string $pc_returned_at = '';
    public string $pc_initialization_allowed_on = '';
    public string $note = '';

    protected function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'retired_at' => 'required|date',
            'used_pc_info' => 'nullable|string|max:255',
            'status' => 'required|in:pending,processing,completed',
            'pc_return_status' => 'required|in:unreturned,returned,lost',
            'pc_returned_at' => 'nullable|date',
            'pc_initialization_allowed_on' => 'nullable|date|after_or_equal:pc_returned_at',
            'note' => 'nullable|string',
        ];
    }

    /**
     * 一覧表示（フィルタ・ページネーション付き）
     */
    public function render()
    {
        $retirements = Retirement::query()
            ->with('user')
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->pcReturnStatusFilter, fn ($q) => $q->where('pc_return_status', $this->pcReturnStatusFilter))
            ->latest('retired_at')
            ->paginate(15);

        // 新規作成用：全社員（除外なし）
        $users = User::orderBy('name')->get();

        return view('livewire.retirements.retirement-manager', [
            'retirements' => $retirements,
            'users' => $users,
        ])->layout('layouts.admin');
    }

    /**
     * 新規作成モーダルを開く
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    /**
     * 編集モーダルを開く
     */
    public function openEditModal(int $id): void
    {
        $retirement = Retirement::findOrFail($id);

        $this->retirementId = $retirement->id;
        $this->user_id = $retirement->user_id;
        $this->retired_at = optional($retirement->retired_at)->format('Y-m-d') ?? '';
        $this->used_pc_info = $retirement->used_pc_info ?? '';
        $this->status = $retirement->status;
        $this->pc_return_status = $retirement->pc_return_status;
        $this->pc_returned_at = optional($retirement->pc_returned_at)->format('Y-m-d') ?? '';
        $this->pc_initialization_allowed_on = optional($retirement->pc_initialization_allowed_on)->format('Y-m-d') ?? '';
        $this->note = $retirement->note ?? '';

        $this->isEditMode = true;
        $this->showModal = true;
    }

    /**
     * 保存（新規作成・更新共通）
     */
    public function save(): void
    {
        $validated = $this->validate();

        // 空文字の日付フィールドはnullに変換（DBがdate型でnullableのため）
        foreach (['pc_returned_at', 'pc_initialization_allowed_on'] as $dateField) {
            if ($validated[$dateField] === '') {
                $validated[$dateField] = null;
            }
        }

        if ($this->isEditMode && $this->retirementId) {
            $retirement = Retirement::findOrFail($this->retirementId);
            $retirement->update($validated);
        } else {
            Retirement::create($validated);
        }

        $this->closeModal();
        $this->resetPage();
    }

    /**
     * 削除
     */
    public function delete(int $id): void
    {
        Retirement::findOrFail($id)->delete();
    }

    /**
     * チェックリストの即時トグル保存（LDAP/GitHub/Slack/Email）
     */
    public function toggleFlag(int $id, string $field): void
    {
        // 想定外のフィールド名が渡された場合の安全策
        $allowed = ['has_ldap_deleted', 'has_github_deleted', 'has_slack_deleted', 'has_email_deleted'];

        if (! in_array($field, $allowed, true)) {
            return;
        }

        $retirement = Retirement::findOrFail($id);
        $retirement->{$field} = ! $retirement->{$field};
        $retirement->save();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'retirementId',
            'user_id',
            'retired_at',
            'used_pc_info',
            'status',
            'pc_return_status',
            'pc_returned_at',
            'pc_initialization_allowed_on',
            'note',
        ]);
        $this->status = 'pending';
        $this->pc_return_status = 'unreturned';
        $this->resetErrorBag();
    }
}