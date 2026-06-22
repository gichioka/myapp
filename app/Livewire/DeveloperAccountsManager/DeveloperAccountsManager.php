<?php

namespace App\Livewire\DeveloperAccountsManager;

use Livewire\Component;
use App\Models\DeveloperAccount;
use App\Models\User;

class DeveloperAccountsManager extends Component
{
    // フォームとバインドするプロパティ
    public $user_id;
    public $tool_type = 'github'; // 初期値
    public $label;
    public $username;
    public $password;
    public $url;

    // コントロール用プロパティ
    public $editingId = null;
    public $search = '';
    public $filterType = '';
    public $message = '';

    /**
     * バリデーションルール
     */
    protected function rules()
    {
        return [
            'user_id'   => 'required|exists:users,id',
            'tool_type' => 'required|in:github,docker,redmine,svn',
            'label'     => 'required|string|max:255',
            'username'  => 'nullable|string|max:255',
            'password'  => 'required|string', // encryptedキャストされるため生データで検証
            'url'       => 'nullable|url|max:255',
        ];
    }

    /**
     * 新規アカウント作成
     */
    public function create()
    {
        $validated = $this->validate();

        DeveloperAccount::create($validated);

        $this->resetForm();
        $this->message = 'アカウントを登録しました。';
    }

    /**
     * 編集モードの開始
     */
    public function edit($id)
    {
        $account = DeveloperAccount::findOrFail($id);
        $this->editingId = $account->id;
        
        // フォームに既存データをセット
        $this->user_id   = $account->user_id;
        $this->tool_type = $account->tool_type;
        $this->label     = $account->label;
        $this->username  = $account->username;
        $this->password  = $account->password; // モデル側で自動復号された文字列が入ります
        $this->url       = $account->url;
    }

    /**
     * アカウント情報の更新
     */
    public function update()
    {
        $validated = $this->validate();
        
        $account = DeveloperAccount::findOrFail($this->editingId);
        $account->update($validated);

        $this->resetForm();
        $this->message = 'アカウント情報を更新しました。';
    }

    /**
     * 編集のキャンセル
     */
    public function cancelEdit()
    {
        $this->resetForm();
    }

    /**
     * フォームのリセット処理
     */
    private function resetForm()
    {
        $this->reset(['user_id', 'label', 'username', 'password', 'url', 'editingId']);
        $this->tool_type = 'github';
    }

    /**
     * アカウントの削除
     */
    public function delete($id)
    {
        DeveloperAccount::findOrFail($id)->delete();
        $this->message = 'アカウントを削除しました。';
    }

    /**
     * 画面のレンダリング
     */
    public function render()
    {
        // 1. 在籍中の全ユーザーを取得（退職者 is_retired = true を除外）
        $users = User::where('is_retired', false)
            ->orderBy('department')
            ->orderBy('name')
            ->get();

        // 2. 検索・フィルター条件に合ったアカウント一覧を取得（Eager LoadingでN+1問題を防止）
        $accountsList = DeveloperAccount::with('user')
            ->when($this->filterType, function($query) {
                $query->where('tool_type', $this->filterType);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('label', 'like', '%'.$this->search.'%')
                      ->orWhere('username', 'like', '%'.$this->search.'%')
                      ->orWhere('url', 'like', '%'.$this->search.'%')
                      // 紐づいているユーザーの名前や部署名でも検索可能にする
                      ->orWhereHas('user', function($userQuery) {
                          $userQuery->where('name', 'like', '%'.$this->search.'%')
                                    ->orWhere('department', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->get();

        // ★修正点：->layout('layouts.admin') を return view() の末尾に正しくチェーンさせました
        return view('livewire.developer-accounts-manager.developer-accounts-manager', [
            'accountsList' => $accountsList,
            'users' => $users,
        ])->layout('layouts.admin');
    }
}