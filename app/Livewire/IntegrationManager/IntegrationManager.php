<?php

namespace App\Livewire\IntegrationManager;

use App\Models\Integration;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IntegrationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    // モーダル制御
    public $showModal = false;
    public $editingId = null;

    // フォームデータ
    public $user_id = null;
    public $type = 'cloud';
    public $provider = null;
    public $project_name = '';
    public $is_active = true;
    public $description = '';

    public $aws_user_arn = '';
    public $gcp_id = '';
    public $azure_oid = '';

    public $redmine_url = '';
    public $redmine_project_name = '';
    public $redmine_api_key = '';

    public $slack_workspace_id = '';
    public $slack_team_name = '';
    public $slack_bot_token = '';

    protected $queryString = ['search', 'typeFilter'];

    public function render()
    {
        $integrations = Integration::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('project_name', 'like', '%' . $this->search . '%')
                      ->orWhere('redmine_url', 'like', '%' . $this->search . '%')
                      ->orWhere('slack_team_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $users = User::orderBy('name')->pluck('name', 'id');

        return view('livewire.integration-manager.integration-manager', [
            'integrations' => $integrations,
            'users'        => $users,
        ])->layout('layouts.admin');
    }

    // 新規登録ボタン用
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    // 編集ボタン用
    public function edit($id)
    {
        $integration = Integration::findOrFail($id);

        $this->editingId    = $integration->id;
        $this->user_id      = $integration->user_id;
        $this->type         = $integration->type;
        $this->provider     = $integration->provider;
        $this->project_name = $integration->project_name ?? '';
        $this->is_active    = $integration->is_active;
        $this->description  = $integration->description ?? '';

        if ($this->type === 'cloud') {
            $this->aws_user_arn = $integration->aws_user_arn ?? '';
            $this->gcp_id       = $integration->gcp_id ?? '';
            $this->azure_oid    = $integration->azure_oid ?? '';
        } elseif ($this->type === 'redmine') {
            $this->redmine_url          = $integration->redmine_url ?? '';
            $this->redmine_project_name = $integration->redmine_project_name ?? '';
            $this->redmine_api_key      = $integration->redmine_api_key ?? '';
        } elseif ($this->type === 'slack') {
            $this->slack_workspace_id = $integration->slack_workspace_id ?? '';
            $this->slack_team_name    = $integration->slack_team_name ?? '';
            $this->slack_bot_token    = $integration->slack_bot_token ?? '';
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'user_id'      => 'required|exists:users,id',
            'type'         => 'required|in:cloud,redmine,slack',
            'project_name' => 'required|string|max:255',
        ]);

        $data = [
            'user_id'      => $this->user_id,
            'type'         => $this->type,
            'project_name' => $this->project_name,
            'is_active'    => $this->is_active,
            'description'  => $this->description,
        ];

        if ($this->type === 'cloud') {
            $data += [
                'provider'     => $this->provider,
                'aws_user_arn' => $this->aws_user_arn,
                'gcp_id'       => $this->gcp_id,
                'azure_oid'    => $this->azure_oid,
            ];
        } elseif ($this->type === 'redmine') {
            $data += [
                'redmine_url'          => $this->redmine_url,
                'redmine_project_name' => $this->redmine_project_name,
                'redmine_api_key'      => $this->redmine_api_key,
            ];
        } elseif ($this->type === 'slack') {
            $data += [
                'slack_workspace_id' => $this->slack_workspace_id,
                'slack_team_name'    => $this->slack_team_name,
                'slack_bot_token'    => $this->slack_bot_token,
            ];
        }

        if ($this->editingId) {
            Integration::findOrFail($this->editingId)->update($data);
            session()->flash('message', '更新しました。');
        } else {
            Integration::create($data);
            session()->flash('message', '登録しました。');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Integration::findOrFail($id)->delete();
        $this->dispatch('notify', message: '削除しました。', type: 'success');
    }

    public function toggleActive($id)
    {
        $integration = Integration::findOrFail($id);
        $integration->update(['is_active' => !$integration->is_active]);
    }

    private function resetForm()
    {
        $this->reset([
            'editingId', 'user_id', 'project_name', 'description',
            'aws_user_arn', 'gcp_id', 'azure_oid',
            'redmine_url', 'redmine_project_name', 'redmine_api_key',
            'slack_workspace_id', 'slack_team_name', 'slack_bot_token',
        ]);
        $this->type      = 'cloud';
        $this->is_active = true;
        $this->provider  = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
}