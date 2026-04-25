<div class="space-y-8 p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div>
        <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">ユーザー管理</h1>
        <p class="text-gray-500 mt-1 text-lg">ユーザー情報の登録・編集・削除</p>
    </div>

    <!-- 検索バー -->
    <div class="relative max-w-md">
        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <input type="text" wire:model.live="search" placeholder="ユーザーを検索..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
    </div>

    <!-- フォームセクション（adminのみ表示） -->
    @role('admin')
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $editingUserId ? 'ユーザー編集' : '新しいユーザーを追加' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" wire:model="name" placeholder="名前"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
            <input type="email" wire:model="email" placeholder="メールアドレス"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
            @if(!$editingUserId)
            <input type="password" wire:model="password" placeholder="パスワード"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
            @endif
            {{-- ✅ 部署を追加 --}}
            <input type="text" wire:model="department" placeholder="部署"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
            <select wire:model="employment_type"
                class="px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition">
                <option value="正社員">正社員</option>
                <option value="契約社員">契約社員</option>
                <option value="アルバイト">アルバイト</option>
                <option value="外部の人">外部の人</option>
            </select>
        </div>

        <textarea wire:model="comment" placeholder="コメント"
            class="w-full mt-4 px-4 py-3 border border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none shadow-sm transition"></textarea>

        <div class="flex items-center mt-4">
            <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model="is_retired" class="w-5 h-5 text-indigo-600 rounded transition">
                <span class="ml-2 text-gray-700 font-medium">退職済み</span>
            </label>
        </div>

        <div class="flex gap-3 mt-6">
            @if($editingUserId)
            <button wire:click="updateUser" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">更新</button>
            <button wire:click="resetInput" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">キャンセル</button>
            @else
            <button wire:click="createUser" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition duration-200 shadow">追加</button>
            @endif
        </div>
    </div>
    @endrole

    <!-- ユーザー一覧テーブル -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">ID</th>
                        <th class="px-6 py-3 text-left font-semibold">名前</th>
                        <th class="px-6 py-3 text-left font-semibold">メール</th>
                        <th class="px-6 py-3 text-left font-semibold">部署</th>
                        <th class="px-6 py-3 text-left font-semibold">雇用形態</th>
                        <th class="px-6 py-3 text-center font-semibold">退職</th>
                        <th class="px-6 py-3 text-left font-semibold">コメント</th>
                        @role('admin')
                        <th class="px-6 py-3 text-center font-semibold">操作</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->department ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($user->employment_type === '正社員') bg-blue-100 text-blue-800
                                @elseif($user->employment_type === '契約社員') bg-purple-100 text-purple-800
                                @elseif($user->employment_type === 'アルバイト') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $user->employment_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                @if($user->is_retired) bg-red-100 text-red-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $user->is_retired ? '退職' : '在職' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 truncate max-w-xs" title="{{ $user->comment }}">{{ $user->comment ?: '-' }}</td>
                        @role('admin')
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button wire:click="editUser({{ $user->id }})"
                                    class="flex items-center gap-2 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    編集
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})" wire:confirm="このユーザーを削除してもよろしいですか？"
                                    class="flex items-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    削除
                                </button>
                            </div>
                        </td>
                        @endrole
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            ユーザーがいません
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ページネーション -->
    <div class="flex justify-center">
        {{ $users->links() }}
    </div>
</div>