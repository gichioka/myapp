<div 
    x-data="{ open: false }"
    x-on:open-modal.window="open = true"
    x-on:close-modal.window="open = false"
    class="p-8 bg-gray-50 min-h-screen"
>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">サーバーアカウント</h1>
            <p class="text-sm text-gray-500 mt-1">サーバーの認証情報を管理</p>
        </div>

        <button 
            @click="$wire.resetForm(); open = true"
            class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            ＋ 新規追加
        </button>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">

        {{-- Table --}}
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Label</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Host</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($accounts as $a)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-600/10">
                                {{ $a->category }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $a->label }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $a->user?->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $a->host ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <button 
                                @click="$wire.edit({{ $a->id }}); open = true"
                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                            >
                                編集
                            </button>

                            <button 
                                wire:click="delete({{ $a->id }})"
                                class="text-red-600 hover:text-red-800"
                            >
                                削除
                            </button>
                        </td>
                    </tr>

                    @if($a->note)
                        <tr class="bg-gray-50">
                            <td colspan="5" class="px-6 py-3 text-sm text-gray-500">
                                📝 {{ $a->note }}
                            </td>
                        </tr>
                    @endif

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-400 text-sm">
                            データがありません
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50"
    >
        <div 
            x-transition
            class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6"
        >

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $isEditing ? '編集' : '新規追加' }}
            </h2>

            <form wire:submit.prevent="save" class="space-y-4">

                {{-- Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                    <input wire:model="category"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ラベル</label>
                    <input wire:model="label"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">アカウント名</label>
                    <input wire:model="account_name"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ユーザー</label>
                    <select wire:model="user_id"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">選択してください</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">パスワード</label>
                    <input type="password" wire:model="password"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ホスト</label>
                    <input wire:model="host"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">メモ</label>
                    <textarea wire:model="note"
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button"
                        @click="open = false"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        キャンセル
                    </button>

                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-500">
                        保存
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>