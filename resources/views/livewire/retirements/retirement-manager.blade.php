<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">退職者管理</h1>
        <button wire:click="openCreateModal" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            退職レコード作成
        </button>
    </div>

    {{-- フィルタ --}}
    <div class="flex gap-4 mb-4">
        <select wire:model.live="statusFilter" class="border rounded px-2 py-1">
            <option value="">手続きステータス（すべて）</option>
            <option value="pending">未処理</option>
            <option value="processing">手続き中</option>
            <option value="completed">完了</option>
        </select>

        <select wire:model.live="pcReturnStatusFilter" class="border rounded px-2 py-1">
            <option value="">PC返却状況（すべて）</option>
            <option value="unreturned">未返却</option>
            <option value="returned">返却済</option>
            <option value="lost">紛失</option>
        </select>
    </div>

    {{-- 一覧テーブル --}}
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-sm">
                <th class="p-2 text-left">社員名</th>
                <th class="p-2 text-left">退職日</th>
                <th class="p-2 text-left">ステータス</th>
                <th class="p-2 text-left">PC返却</th>
                <th class="p-2 text-left">PC初期化可能日</th>
                <th class="p-2 text-center">LDAP</th>
                <th class="p-2 text-center">GitHub</th>
                <th class="p-2 text-center">Slack</th>
                <th class="p-2 text-center">Email</th>
                <th class="p-2 text-left">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($retirements as $retirement)
                <tr class="border-b text-sm" wire:key="retirement-{{ $retirement->id }}">
                    <td class="p-2">{{ $retirement->user->name ?? '(削除済み社員)' }}</td>
                    <td class="p-2">{{ $retirement->retired_at?->format('Y-m-d') }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-xs
                            @class([
                                'bg-gray-200' => $retirement->status === 'pending',
                                'bg-yellow-200' => $retirement->status === 'processing',
                                'bg-green-200' => $retirement->status === 'completed',
                            ])">
                            {{ match($retirement->status) {
                                'pending' => '未処理',
                                'processing' => '手続き中',
                                'completed' => '完了',
                            } }}
                        </span>
                    </td>
                    <td class="p-2">
                        {{ match($retirement->pc_return_status) {
                            'unreturned' => '未返却',
                            'returned' => '返却済',
                            'lost' => '紛失',
                        } }}
                    </td>
                    <td class="p-2">
                        @if ($retirement->pc_initialization_allowed_on)
                            <span class="{{ $retirement->pc_initialization_allowed_on->isPast() ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $retirement->pc_initialization_allowed_on->format('Y-m-d') }}
                            </span>
                        @endif
                    </td>

                    {{-- チェックリスト：トグルボタン --}}
                    @foreach (['has_ldap_deleted', 'has_github_deleted', 'has_slack_deleted', 'has_email_deleted'] as $flag)
                        <td class="p-2 text-center">
                            <button
                                wire:click="toggleFlag({{ $retirement->id }}, '{{ $flag }}')"
                                wire:loading.attr="disabled"
                                wire:target="toggleFlag({{ $retirement->id }}, '{{ $flag }}')"
                                class="w-6 h-6 rounded border flex items-center justify-center
                                    {{ $retirement->{$flag} ? 'bg-green-500 text-white border-green-600' : 'bg-white border-gray-300' }}"
                            >
                                @if ($retirement->{$flag})
                                    ✓
                                @endif
                            </button>
                        </td>
                    @endforeach

                    <td class="p-2 space-x-2">
                        <button wire:click="openEditModal({{ $retirement->id }})" class="text-blue-600 hover:underline">編集</button>
                        <button
                            wire:click="delete({{ $retirement->id }})"
                            wire:confirm="このレコードを削除してよろしいですか？"
                            class="text-red-600 hover:underline"
                        >削除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $retirements->links() }}
    </div>

    {{-- 作成/編集モーダル --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                <h2 class="text-lg font-bold mb-4">
                    {{ $isEditMode ? '退職レコード編集' : '退職レコード作成' }}
                </h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm mb-1">社員</label>
                        <select wire:model="user_id" class="w-full border rounded px-2 py-1" {{ $isEditMode ? 'disabled' : '' }}>
                            <option value="">選択してください</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">退職日</label>
                        <input type="date" wire:model="retired_at" class="w-full border rounded px-2 py-1">
                        @error('retired_at') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">使用PC情報</label>
                        <input type="text" wire:model="used_pc_info" class="w-full border rounded px-2 py-1">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">手続きステータス</label>
                        <select wire:model="status" class="w-full border rounded px-2 py-1">
                            <option value="pending">未処理</option>
                            <option value="processing">手続き中</option>
                            <option value="completed">完了</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">PC返却状況</label>
                        <select wire:model="pc_return_status" class="w-full border rounded px-2 py-1">
                            <option value="unreturned">未返却</option>
                            <option value="returned">返却済</option>
                            <option value="lost">紛失</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">PC回収日</label>
                        <input type="date" wire:model="pc_returned_at" class="w-full border rounded px-2 py-1">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">PC初期化可能日</label>
                        <input type="date" wire:model="pc_initialization_allowed_on" class="w-full border rounded px-2 py-1">
                        @error('pc_initialization_allowed_on') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm mb-1">備考</label>
                        <textarea wire:model="note" class="w-full border rounded px-2 py-1" rows="3"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="closeModal" class="px-4 py-2 border rounded">キャンセル</button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
                </div>
            </div>
        </div>
    @endif
</div>