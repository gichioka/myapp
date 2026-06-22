<?php

namespace App\Livewire\ServerAccount;

use Livewire\Component;
use App\Models\ServerAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class Manager extends Component
{
    public $category = '';
    public $label = '';
    public $account_name = '';
    public $user_id = '';
    public $password = '';
    public $host = '';
    public $note = '';

    public $editingId = null;
    public $isEditing = false;

    public $searchTerm = '';
    public $filterCategory = '';

    protected function rules()
    {
        return [
            'category'     => 'required|string|max:255',
            'label'        => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'user_id'      => 'required|exists:users,id',
            'password'     => $this->isEditing
                ? 'nullable|string'
                : 'required|string',
            'host'         => 'nullable|string|max:255',
            'note'         => 'nullable|string',
        ];
    }

    public function render()
    {
        $accounts = ServerAccount::with('user')
            ->search($this->searchTerm)
            ->byCategory($this->filterCategory)
            ->orderBy('category')
            ->orderBy('label')
            ->get();

        return view('livewire.server-account.manager', [
            'accounts'   => $accounts,
            'categories' => ServerAccount::query()
                ->select('category')
                ->distinct()
                ->pluck('category'),
            'users'      => User::orderBy('name')->get(),
        ])
        ->layout('layouts.admin'); // ← adminレイアウト
    }

    public function save()
    {
        $this->validate();

        try {

            $data = [
                'category'     => $this->category,
                'label'        => $this->label,
                'account_name' => $this->account_name,
                'user_id'      => $this->user_id,
                'host'         => $this->host,
                'note'         => $this->note,
            ];

            if (!empty($this->password)) {
                $data['password'] = bcrypt($this->password);
            }

            if ($this->isEditing) {

                ServerAccount::findOrFail($this->editingId)
                    ->update($data);

                session()->flash('message', '更新しました');

            } else {

                ServerAccount::create($data);

                session()->flash('message', '追加しました');
            }

            $this->resetForm();

            $this->dispatch('close-modal');

        } catch (\Throwable $e) {

            Log::error($e);

            session()->flash(
                'error',
                'エラーが発生しました'
            );
        }
    }

    public function edit($id)
    {
        $account = ServerAccount::findOrFail($id);

        $this->editingId = $account->id;
        $this->isEditing = true;

        $this->category = $account->category;
        $this->label = $account->label;
        $this->account_name = $account->account_name;
        $this->user_id = $account->user_id;
        $this->password = '';
        $this->host = $account->host;
        $this->note = $account->note;

        $this->dispatch('open-modal');
    }

    public function delete($id)
    {
        ServerAccount::findOrFail($id)->delete();

        session()->flash(
            'message',
            '削除しました'
        );
    }

    public function resetForm()
    {
        $this->reset([
            'category',
            'label',
            'account_name',
            'user_id',
            'password',
            'host',
            'note',
            'editingId',
            'isEditing',
        ]);
    }
}