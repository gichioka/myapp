<div>
    {{-- 🔍 検索 --}}
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border">
        <div class="flex gap-2 items-center">
            <div class="relative w-full">
                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="品名・SKU・CPU・メモリで検索"
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button
                wire:click="resetSearch"
                class="px-6 py-2 bg-gray-500 text-white font-bold rounded-lg shadow hover:bg-gray-600 transition"
            >
                クリア
            </button>
        </div>
        <div wire:loading wire:target="search" class="text-sm text-gray-500 mt-2">
            検索中...
        </div>
    </div>

    {{-- 📝 登録フォーム --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
        <h3 class="text-lg font-bold mb-4 pb-2 border-b">
            {{ $isEdit ? '✏️ PC情報を編集' : '➕ 新規PC登録' }}
        </h3>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <input wire:model="name" type="text" placeholder="品名 *" class="border rounded p-2">
            <input wire:model="brand" type="text" placeholder="ブランド" class="border rounded p-2">
            <input wire:model="sku" type="text" placeholder="SKU *" class="border rounded p-2">
            <input wire:model="category" type="text" placeholder="カテゴリ" class="border rounded p-2">
            <input wire:model="cpu" type="text" placeholder="CPU" class="border rounded p-2">
            <input wire:model="ram" type="number" placeholder="メモリ(GB)" class="border rounded p-2">
            <input wire:model="storage" type="text" placeholder="ストレージ" class="border rounded p-2">
            <input wire:model="quantity" type="number" placeholder="在庫数 *" class="border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-1">使用者</label>
            <select wire:model="user_id" class="w-full border rounded p-2">
                <option value="">未割当</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <textarea wire:model="description" placeholder="備考" rows="3" class="w-full border rounded p-2 mb-4"></textarea>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex gap-2">
            @if($isEdit)
                <button wire:click="update" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    更新
                </button>
                <button wire:click="cancelEdit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                    キャンセル
                </button>
            @else
                <button wire:click="save" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    登録
                </button>
            @endif
        </div>
    </div>

    {{-- 📋 一覧 --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">品名</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">SKU</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">CPU</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">RAM</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">在庫</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">備考</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">使用者</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600 border-b">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm font-mono">{{ $product->sku }}</td>
                            <td class="px-4 py-3 text-sm">{{ $product->cpu }}</td>
                            <td class="px-4 py-3 text-sm">{{ $product->ram }}GB</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="{{ $product->quantity == 0 ? 'text-red-600 font-bold' : ($product->quantity < 5 ? 'text-orange-600' : '') }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ \Illuminate\Support\Str::limit($product->description, 30) ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                                    {{ $product->user->name ?? '未割当' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex gap-2">
                                    <button wire:click="edit({{ $product->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 transition">
                                        編集
                                    </button>
                                    <button wire:click="delete({{ $product->id }})" wire:confirm="本当に削除しますか？" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition">
                                        削除
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                📭 データがありません
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t">
            {{ $products->links() }}
        </div>
    </div>
</div>