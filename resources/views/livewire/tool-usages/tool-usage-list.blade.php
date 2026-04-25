<div class="space-y-8 p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div>
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">ツール使用状況</h1>
        <p class="text-gray-500 mt-1 text-lg">誰がどのツールを使っているか管理</p>
    </div>

    <!-- 検索バー -->
    <div class="relative max-w-md">
        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <input type="text" wire:model.live="search" placeholder="ツール名・ユーザー名で検索..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
    </div>

    <!-- フォームセクション（全員） -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $editingId ? 'ツール使用状況を編集' : 'ツール使用状況を追加' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select wire:model="user_id"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
                <option value="">ユーザーを選択...</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}（{{ $user->email }}）</option>
                @endforeach
            </select>

            <input type="text" wire:model="tool_name" placeholder="ツール名（例: Unity, Redmine）"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">

            <input type="text" wire:model="version" placeholder="バージョン（例: 2023.1.0）"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
        </div>

        <textarea wire:model="memo" placeholder="メモ・備考"
            class="w-full mt-4 px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition" rows="3"></textarea>

        <div class="flex gap-3 mt-6">
            @if($editingId)
                <button wire:click="updateToolUsage"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">更新</button>
                <button wire:click="resetInput"
                    class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">キャンセル</button>
            @else
                <button wire:click="createToolUsage"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">追加</button>
            @endif
        </div>
    </div>

    <!-- 一覧テーブル -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">ユーザー</th>
                        <th class="px-6 py-3 text-left font-semibold">ツール名</th>
                        <th class="px-6 py-3 text-left font-semibold">バージョン</th>
                        <th class="px-6 py-3 text-left font-semibold">メモ</th>
                        @role('admin')
                        <th class="px-6 py-3 text-center font-semibold">操作</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($toolUsages as $toolUsage)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">{{ $toolUsage->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $toolUsage->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $toolUsage->tool_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $toolUsage->version ?: '-' }}</td>
                        <td class="px-6 py-4 truncate max-w-xs" title="{{ $toolUsage->memo }}">{{ $toolUsage->memo ?: '-' }}</td>
                        @role('admin')
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button wire:click="editToolUsage({{ $toolUsage->id }})"
                                    class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    編集
                                </button>
                                <button wire:click="deleteToolUsage({{ $toolUsage->id }})" wire:confirm="この記録を削除してもよろしいですか？"
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    削除
                                </button>
                            </div>
                        </td>
                        @endrole
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            データがありません
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ページネーション -->
    <div class="flex justify-center">
        {{ $toolUsages->links() }}
    </div>
</div>