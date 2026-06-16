<div>
    {{-- フラッシュメッセージ --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700">✕</button>
        </div>
    @endif

    {{-- ==================== ヘッダー ==================== --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">連携管理</h2>
            <p class="text-sm text-gray-500 mt-1">Cloud・Redmine・Slack の連携設定を管理します</p>
        </div>
        
        <button 
            wire:click="openModal"
            class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            新規登録
        </button>
    </div>

    {{-- ==================== 検索・フィルタ ==================== --}}
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <div class="flex gap-4">
            <div class="flex-1 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="名前を検索..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            </div>
            
            <select wire:model.live="typeFilter" 
                    class="border border-gray-300 rounded-lg px-4 py-2.5 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                <option value="">🔍 すべてのタイプ</option>
                <option value="cloud">☁️ Cloud</option>
                <option value="redmine">🔴 Redmine</option>
                <option value="slack">💬 Slack</option>
            </select>
            
            <button wire:click="resetFilters" class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                クリア
            </button>
        </div>
    </div>

    {{-- ==================== 一覧テーブル ==================== --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">名前</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">エラー名</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">タイプ</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">プロジェクト名</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">詳細</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ステータス</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($integrations as $integration)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($integration->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $integration->user->name ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-red-600">
                                {{ $integration->error_name ?? 'GABAKU GIK' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeClass = match($integration->type) {
                                    'cloud' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'redmine' => 'bg-green-100 text-green-700 border-green-200',
                                    'slack' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200'
                                };
                                $typeIcon = match($integration->type) {
                                    'cloud' => '☁️',
                                    'redmine' => '🔴',
                                    'slack' => '💬',
                                    default => '📦'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full border {{ $typeClass }}">
                                {{ $typeIcon }} {{ ucfirst($integration->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">{{ $integration->project_name ?? 'mq' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if ($integration->type === 'cloud')
                                <span class="inline-flex items-center gap-1">
                                    <span class="font-semibold">{{ strtoupper($integration->provider ?? 'AWS') }}</span>
                                </span>
                            @elseif ($integration->type === 'redmine')
                                <span class="text-xs truncate max-w-[200px] inline-block" title="{{ $integration->redmine_url ?? '' }}">
                                    {{ Str::limit($integration->redmine_url ?? 'https://redmine.example.com', 30) }}
                                </span>
                            @elseif ($integration->type === 'slack')
                                {{ $integration->slack_team_name ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button 
                                wire:click="toggleActive({{ $integration->id }})"
                                class="px-3 py-1.5 text-xs font-semibold rounded-full transition-all duration-200 shadow-sm
                                    {{ $integration->is_active 
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-200' 
                                        : 'bg-gray-100 text-gray-500 hover:bg-gray-200 border border-gray-200' }}">
                                {{ $integration->is_active ? '✅ 有効' : '⭕ 無効' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button 
                                    wire:click="edit({{ $integration->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg text-sm font-medium transition-colors border border-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    編集
                                </button>
                                <button 
                                    wire:click="delete({{ $integration->id }})"
                                    wire:confirm="本当に削除しますか？"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-sm font-medium transition-colors border border-red-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    削除
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl">
                                    🔌
                                </div>
                                <span class="text-gray-500">まだ連携情報が登録されていません。</span>
                                <button 
                                    wire:click="openModal"
                                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                    ＋ 最初の連携を登録する
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="mt-6">
        {{ $integrations->links() }}
    </div>

    {{-- ==================== モーダル ==================== --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" 
             x-data="{ open: true }" 
             x-show="open"
             x-on:click.away="open = false"
             x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $editingId ? '✏️ 連携情報を編集' : '➕ 新しい連携情報を登録' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form wire:submit="save" class="space-y-5">
                        {{-- ユーザー名 --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                名前 <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="user_id" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="">-- ユーザーを選択 --</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- エラー名 --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                エラー名
                            </label>
                            <input type="text" wire:model="error_name" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                   placeholder="例: GABAKU GIK">
                            @error('error_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- タイプ --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                タイプ <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="type" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="cloud">☁️ Cloud</option>
                                <option value="redmine">🔴 Redmine</option>
                                <option value="slack">💬 Slack</option>
                            </select>
                        </div>

                        {{-- プロジェクト名 --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                プロジェクト名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="project_name" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                   placeholder="例: mq">
                            @error('project_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cloud固有 --}}
                        @if($type === 'cloud')
                            <div class="bg-blue-50 rounded-xl p-4 space-y-4 border border-blue-200">
                                <p class="text-sm font-semibold text-blue-700 flex items-center gap-2">
                                    <span>☁️</span> Cloud 設定
                                </p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                                    <select wire:model.live="provider" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                        <option value="">-- 選択してください --</option>
                                        <option value="aws">AWS</option>
                                        <option value="gcp">GCP</option>
                                        <option value="azure">Azure</option>
                                    </select>
                                    @error('provider')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if($provider === 'aws')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">AWS User ARN</label>
                                        <input type="text" wire:model="aws_user_arn" placeholder="arn:aws:iam::123456789012:user/username"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                        @error('aws_user_arn')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($provider === 'gcp')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">GCP Service Account ID</label>
                                        <input type="text" wire:model="gcp_id" placeholder="service-account@project.iam.gserviceaccount.com"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                        @error('gcp_id')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif($provider === 'azure')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Azure Object ID</label>
                                        <input type="text" wire:model="azure_oid" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                        @error('azure_oid')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Redmine固有 --}}
                        @if($type === 'redmine')
                            <div class="bg-green-50 rounded-xl p-4 space-y-4 border border-green-200">
                                <p class="text-sm font-semibold text-green-700 flex items-center gap-2">
                                    <span>🔴</span> Redmine 設定
                                </p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Redmine URL</label>
                                    <input type="text" wire:model="redmine_url" placeholder="https://redmine.example.com"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('redmine_url')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Identifier</label>
                                    <input type="text" wire:model="redmine_project_identifier" placeholder="project-identifier"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('redmine_project_identifier')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">API Key（暗号化推奨）</label>
                                    <input type="password" wire:model="redmine_api_key" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('redmine_api_key')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        {{-- Slack固有 --}}
                        @if($type === 'slack')
                            <div class="bg-purple-50 rounded-xl p-4 space-y-4 border border-purple-200">
                                <p class="text-sm font-semibold text-purple-700 flex items-center gap-2">
                                    <span>💬</span> Slack 設定
                                </p>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Workspace ID</label>
                                    <input type="text" wire:model="slack_workspace_id" placeholder="T0XXXXXXX"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('slack_workspace_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Team Name</label>
                                    <input type="text" wire:model="slack_team_name" placeholder="My Team"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('slack_team_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bot Token（暗号化必須）</label>
                                    <input type="password" wire:model="slack_bot_token" placeholder="xoxb-xxxxxxxx-xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxx"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('slack_bot_token')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slack User ID</label>
                                    <input type="text" wire:model="slack_user_id" placeholder="U0XXXXXXX"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                                    @error('slack_user_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        {{-- 有効フラグ --}}
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" wire:model="is_active" id="is_active" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">この連携を有効にする</label>
                        </div>

                        {{-- ボタン --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="closeModal"
                                    class="px-5 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors">
                                キャンセル
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200">
                                {{ $editingId ? '更新する' : '登録する' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        
        tbody tr {
            transition: all 0.2s ease;
        }
        
        button {
            cursor: pointer;
        }
    </style>
</div>