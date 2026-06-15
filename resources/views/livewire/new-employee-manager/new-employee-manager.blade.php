<div class="p-6 space-y-6">

    {{-- フラッシュメッセージ --}}
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- 統計バッジ --}}
    <div class="flex gap-4">
        <span class="rounded-full bg-blue-100 text-blue-800 px-3 py-1 text-sm font-medium">予定: {{ $stats['予定'] }}</span>
        <span class="rounded-full bg-green-100 text-green-800 px-3 py-1 text-sm font-medium">入社済: {{ $stats['入社済'] }}</span>
        <span class="rounded-full bg-red-100 text-red-800 px-3 py-1 text-sm font-medium">辞退: {{ $stats['辞退'] }}</span>
    </div>

    {{-- 検索・フィルター --}}
    <div class="flex flex-wrap gap-3 items-center">
        <input wire:model.live.debounce.300ms="search"
               type="text" placeholder="氏名 / 申請者 / 部署で検索"
               class="border rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <select wire:model.live="filterStatus"
                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">すべてのステータス</option>
            @foreach (\App\Models\NewEmployee::STATUSES as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterDepartment"
                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">すべての部署</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept }}">{{ $dept }}</option>
            @endforeach
        </select>

        <button wire:click="openCreateModal"
                class="ml-auto bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
            ＋ 新規登録
        </button>
    </div>

    {{-- テーブル --}}
    <div class="overflow-x-auto rounded-lg border">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">入社者氏名</th>
                    <th class="px-4 py-3 text-left">申請者</th>
                    <th class="px-4 py-3 text-left">部署</th>
                    <th class="px-4 py-3 text-left">入社日</th>
                    <th class="px-4 py-3 text-left">ステータス</th>
                    <th class="px-4 py-3 text-left">ツール</th>
                    <th class="px-4 py-3 text-left">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($employees as $emp)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $emp->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $emp->applicant_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $emp->department ?? '―' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $emp->join_date->format('Y/m/d') }}</td>
                        <td class="px-4 py-3">
                            <select wire:change="updateStatus({{ $emp->id }}, $event.target.value)"
                                    class="border rounded px-2 py-1 text-xs
                                        {{ $emp->status === '入社済' ? 'bg-green-50 text-green-700 border-green-300' :
                                           ($emp->status === '辞退'  ? 'bg-red-50 text-red-700 border-red-300' :
                                                                        'bg-blue-50 text-blue-700 border-blue-300') }}">
                                @foreach (\App\Models\NewEmployee::STATUSES as $s)
                                    <option value="{{ $s }}" @selected($emp->status === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach (\App\Models\NewEmployee::TOOLS as $field => $label)
                                    @if ($emp->$field)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs">{{ $label }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $emp->id }})"
                                        class="text-blue-600 hover:underline text-xs">編集</button>
                                <button wire:click="delete({{ $emp->id }})"
                                        wire:confirm="削除してよろしいですか？"
                                        class="text-red-500 hover:underline text-xs">削除</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">データがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div>{{ $employees->links() }}</div>

    {{-- モーダル --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $isEditing ? '入社予定者を編集' : '入社予定者を登録' }}
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">入社者氏名 <span class="text-red-500">*</span></label>
                        <input wire:model="name" type="text"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror" />
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">申請者名 <span class="text-red-500">*</span></label>
                        <input wire:model="applicant_name" type="text"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('applicant_name') border-red-400 @enderror" />
                        @error('applicant_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">メールアドレス</label>
                        <input wire:model="email" type="email"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror" />
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">部署</label>
                        <input wire:model="department" type="text"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">入社日 <span class="text-red-500">*</span></label>
                        <input wire:model="join_date" type="date"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('join_date') border-red-400 @enderror" />
                        @error('join_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs text-gray-600 mb-1">ステータス</label>
                        <select wire:model="status"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach (\App\Models\NewEmployee::STATUSES as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs text-gray-600 mb-2">ツール利用</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach (\App\Models\NewEmployee::TOOLS as $field => $label)
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input wire:model="{{ $field }}" type="checkbox"
                                           class="rounded border-gray-300 text-blue-600" />
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">備考</label>
                        <textarea wire:model="remarks" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="$set('showModal', false)"
                            class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 transition">
                        キャンセル
                    </button>
                    <button wire:click="save"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        {{ $isEditing ? '更新' : '登録' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>