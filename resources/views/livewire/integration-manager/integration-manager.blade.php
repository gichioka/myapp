<div class="space-y-6">
    {{-- ヘッダー --}}
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">連携管理（Cloud・Redmine・Slack）</h2>
        
        <button 
            wire:click="openModal"
            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 flex items-center gap-2 font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            新規登録
        </button>
    </div>

    {{-- 検索・フィルタ --}}
    <div class="flex gap-4">
        <input 
            type="text" 
            wire:model.live.debounce="search" 
            placeholder="プロジェクト名・URLなどで検索..."
            class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500">

        <select wire:model.live="typeFilter" 
                class="border border-gray-300 rounded-xl px-4 py-3 bg-white">
            <option value="">すべて</option>
            <option value="cloud">Cloud</option>
            <option value="redmine">Redmine</option>
            <option value="slack">Slack</option>
        </select>
    </div>

    {{-- 一覧テーブル --}}
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ユーザー名</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タイプ</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">プロジェクト名</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">詳細</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($integrations as $integration)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $integration->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeClass = match($integration->type) {
                                    'cloud' => 'bg-blue-100 text-blue-700',
                                    'redmine' => 'bg-green-100 text-green-700',
                                    'slack' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $typeClass }}">
                                {{ ucfirst($integration->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $integration->project_name ?? '未設定' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if ($integration->type === 'cloud')
                                {{ $integration->provider ?? '-' }}
                            @elseif ($integration->type === 'redmine')
                                {{ Str::limit($integration->redmine_url ?? '', 40) }}
                            @elseif ($integration->type === 'slack')
                                {{ $integration->slack_team_name ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button 
                                wire:click="toggleActive({{ $integration->id }})"
                                class="px-4 py-1 text-xs font-medium rounded-full transition-colors
                                    {{ $integration->is_active 
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' 
                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $integration->is_active ? '有効' : '無効' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right space-x-4">
                            <button 
                                wire:click="edit({{ $integration->id }})"
                                class="text-blue-600 hover:text-blue-800 font-medium">編集</button>
                            <button 
                                wire:click="delete({{ $integration->id }})"
                                onclick="return confirm('本当に削除しますか？')"
                                class="text-red-600 hover:text-red-800 font-medium">削除</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                            まだ連携情報が登録されていません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="flex justify-center">
        {{ $integrations->links() }}
    </div>

    {{-- モーダル --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-6">
                        {{ $editingId ? '連携情報を編集' : '新しい連携情報を登録' }}
                    </h2>

                    <form wire:submit="save" class="space-y-5">

                        {{-- ユーザー名 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ユーザー名 <span class="text-red-500">*</span></label>
                            <select wire:model="user_id" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                <option value="">-- ユーザーを選択 --</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- タイプ --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">タイプ <span class="text-red-500">*</span></label>
                            <select wire:model.live="type" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                <option value="cloud">Cloud</option>
                                <option value="redmine">Redmine</option>
                                <option value="slack">Slack</option>
                            </select>
                        </div>

                        {{-- プロジェクト名 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">プロジェクト名 <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="project_name" 
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3" placeholder="例: 本番プロジェクト">
                            @error('project_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cloud固有 --}}
                        @if($type === 'cloud')
                            <div class="bg-blue-50 rounded-xl p-4 space-y-4">
                                <p class="text-xs font-medium text-blue-700">Cloud 設定</p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                                    <select wire:model.live="provider" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                        <option value="">-- 選択 --</option>
                                        <option value="aws">AWS</option>
                                        <option value="gcp">GCP</option>
                                        <option value="azure">Azure</option>
                                    </select>
                                </div>
                                @if($provider === 'aws')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">AWS User ARN</label>
                                        <input type="text" wire:model="aws_user_arn" placeholder="arn:aws:iam::..."
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                    </div>
                                @elseif($provider === 'gcp')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">GCP ID</label>
                                        <input type="text" wire:model="gcp_id"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                    </div>
                                @elseif($provider === 'azure')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Azure OID</label>
                                        <input type="text" wire:model="azure_oid"
                                               class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Redmine固有 --}}
                        @if($type === 'redmine')
                            <div class="bg-green-50 rounded-xl p-4 space-y-4">
                                <p class="text-xs font-medium text-green-700">Redmine 設定</p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Redmine URL</label>
                                    <input type="text" wire:model="redmine_url" placeholder="https://redmine.example.com"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Identifier</label>
                                    <input type="text" wire:model="redmine_project_identifier"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key（暗号化推奨）</label>
                                    <input type="password" wire:model="redmine_api_key"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                            </div>
                        @endif

                        {{-- Slack固有 --}}
                        @if($type === 'slack')
                            <div class="bg-purple-50 rounded-xl p-4 space-y-4">
                                <p class="text-xs font-medium text-purple-700">Slack 設定</p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Workspace ID</label>
                                    <input type="text" wire:model="slack_workspace_id" placeholder="T0XXXXXXX"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Team Name</label>
                                    <input type="text" wire:model="slack_team_name"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bot Token（暗号化必須）</label>
                                    <input type="password" wire:model="slack_bot_token" placeholder="xoxb-..."
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slack User ID</label>
                                    <input type="text" wire:model="slack_user_id" placeholder="U0XXXXXXX"
                                           class="w-full border border-gray-300 rounded-xl px-4 py-3">
                                </div>
                            </div>
                        @endif

                        {{-- 有効フラグ --}}
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="is_active" id="is_active" class="w-4 h-4">
                            <label for="is_active" class="text-sm font-medium text-gray-700">有効にする</label>
                        </div>

                        <div class="flex justify-end gap-3 pt-6">
                            <button type="button" wire:click="closeModal"
                                    class="px-6 py-3 text-gray-600 hover:bg-gray-100 rounded-xl font-medium">
                                キャンセル
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700">
                                {{ $editingId ? '更新する' : '登録する' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>