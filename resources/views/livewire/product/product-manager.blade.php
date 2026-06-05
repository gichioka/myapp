<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
        PC在庫管理
    </h1>

    {{-- 🔍 検索 --}}
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border">

        <div class="flex gap-2 items-center">

            <div class="relative w-full">

                <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>

                <input
                    type="text"
                    wire:model="search"
                    wire:keydown.enter="$refresh"
                    placeholder="品名・SKU・CPU・メモリで検索"
                    class="w-full pl-10 pr-4 py-2 border rounded-lg
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>

            <button
                wire:click="$refresh"
                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600
                       text-white font-bold rounded-lg shadow
                       hover:scale-105 transition"
            >
                検索
            </button>

        </div>

        <div wire:loading wire:target="$refresh" class="text-sm text-gray-500 mt-2">
            検索中...
        </div>

    </div>

    {{-- 📝 登録フォーム --}}
    <div class="grid grid-cols-2 gap-4 mb-4">

        <input wire:model="name" type="text" placeholder="品名" class="border rounded p-2">
        <input wire:model="brand" type="text" placeholder="ブランド" class="border rounded p-2">
        <input wire:model="sku" type="text" placeholder="SKU" class="border rounded p-2">
        <input wire:model="category" type="text" placeholder="カテゴリ" class="border rounded p-2">
        <input wire:model="cpu" type="text" placeholder="CPU" class="border rounded p-2">
        <input wire:model="ram" type="number" placeholder="メモリ(GB)" class="border rounded p-2">
        <input wire:model="storage" type="text" placeholder="ストレージ" class="border rounded p-2">
        <input wire:model="quantity" type="number" placeholder="在庫数" class="border rounded p-2">
        <input wire:model="unit_price" type="number" placeholder="単価" class="border rounded p-2">

    </div>

    {{-- 🧑 使用者選択 --}}
    <div class="mb-4">

        <label class="block text-sm font-semibold text-gray-600 mb-1">
            使用者
        </label>

        <select
            wire:model="user_id"
            class="w-full border rounded p-2"
        >
            <option value="">未割当</option>

            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }}
                </option>
            @endforeach

        </select>

    </div>

    {{-- 備考 --}}
    <textarea
        wire:model="description"
        placeholder="備考"
        class="w-full border rounded p-2 mb-4"
    ></textarea>

    {{-- ボタン --}}
    @if($isEdit)
        <button wire:click="update" class="bg-blue-600 text-white px-4 py-2 rounded">
            更新
        </button>
    @else
        <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded">
            登録
        </button>
    @endif

    <hr class="my-6">

    {{-- 📋 一覧 --}}
    <table class="w-full border-collapse border">

        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">品名</th>
                <th class="border p-2">SKU</th>
                <th class="border p-2">CPU</th>
                <th class="border p-2">RAM</th>
                <th class="border p-2">在庫</th>
                <th class="border p-2">単価</th>
                <th class="border p-2">備考</th>
                <th class="border p-2">使用者</th>
                <th class="border p-2">操作</th>
            </tr>
        </thead>

        <tbody>

        @forelse($products as $product)

            <tr>

                <td class="border p-2">{{ $product->name }}</td>
                <td class="border p-2">{{ $product->sku }}</td>
                <td class="border p-2">{{ $product->cpu }}</td>
                <td class="border p-2">{{ $product->ram }}GB</td>

                <td class="border p-2 {{ $product->quantity == 0 ? 'text-red-500 font-bold' : '' }}">
                    {{ $product->quantity }}
                </td>

                <td class="border p-2">
                    ¥{{ number_format($product->unit_price) }}
                </td>

                <td class="border p-2">
                    {{ \Illuminate\Support\Str::limit($product->description, 20) ?? '-' }}
                </td>

                <td class="border p-2">
                    {{ $product->user->name ?? '未割当' }}
                </td>

                <td class="border p-2">

                    <button
                        wire:click="edit({{ $product->id }})"
                        class="bg-yellow-500 text-white px-2 py-1 rounded"
                    >
                        編集
                    </button>

                    <button
                        wire:click="delete({{ $product->id }})"
                        class="bg-red-500 text-white px-2 py-1 rounded"
                    >
                        削除
                    </button>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center p-4">
                    データがありません
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

</div>