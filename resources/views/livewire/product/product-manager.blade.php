<div class="p-6 bg-gray-50 min-h-screen">

    {{-- タイトル --}}
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                💻 PC資産管理
            </h1>

            <p class="text-gray-500 mt-1">
                PCの利用者・在庫・スペックを管理
            </p>
        </div>

    </div>

    {{-- 検索カード --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-lg font-semibold">
                🔍 検索
            </h2>

            <span class="text-sm text-gray-500">
                検索結果 {{ $products->total() }} 件
            </span>

        </div>

        <div class="relative">

            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="品名・CPU・メモリで検索..."
                class="w-full border border-gray-300 rounded-lg p-3 pl-10
                       focus:ring-2 focus:ring-blue-500
                       focus:border-blue-500"
            >

            <span class="absolute left-3 top-3.5 text-gray-400">
                🔍
            </span>

        </div>

    </div>

    {{-- 登録フォーム --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">

        <h2 class="text-lg font-semibold mb-4">
            {{ $isEdit ? '✏️ PC編集' : '➕ PC登録' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- 利用者 --}}
            <select
                wire:model="user_id"
                class="border rounded-lg p-3"
            >
                <option value="">
                    利用者を選択
                </option>

                @foreach($users as $user)

                    <option value="{{ $user->id }}">
                        {{ $user->name }}
                    </option>

                @endforeach

            </select>

            {{-- 品名 --}}
            <input
                wire:model="name"
                type="text"
                placeholder="品名"
                class="border rounded-lg p-3"
            >

            {{-- ブランド --}}
            <input
                wire:model="brand"
                type="text"
                placeholder="ブランド"
                class="border rounded-lg p-3"
            >

            {{-- SKU --}}
            <input
                wire:model="sku"
                type="text"
                placeholder="管理番号(SKU)"
                class="border rounded-lg p-3"
            >

            {{-- カテゴリ --}}
            <input
                wire:model="category"
                type="text"
                placeholder="カテゴリ"
                class="border rounded-lg p-3"
            >

            {{-- CPU --}}
            <input
                wire:model="cpu"
                type="text"
                placeholder="CPU"
                class="border rounded-lg p-3"
            >

            {{-- RAM --}}
            <input
                wire:model="ram"
                type="number"
                placeholder="メモリ(GB)"
                class="border rounded-lg p-3"
            >

            {{-- ストレージ --}}
            <input
                wire:model="storage"
                type="text"
                placeholder="ストレージ"
                class="border rounded-lg p-3"
            >

            {{-- 在庫数 --}}
            <input
                wire:model="quantity"
                type="number"
                placeholder="在庫数"
                class="border rounded-lg p-3"
            >

            {{-- 単価 --}}
            <input
                wire:model="unit_price"
                type="number"
                placeholder="単価"
                class="border rounded-lg p-3"
            >

        </div>

        <textarea
            wire:model="description"
            placeholder="備考"
            rows="4"
            class="w-full border rounded-lg p-3 mt-4"
        ></textarea>

        <div class="mt-4">

            @if($isEdit)

                <button
                    wire:click="update"
                    class="bg-blue-600 hover:bg-blue-700
                           text-white px-6 py-2 rounded-lg"
                >
                    更新
                </button>

            @else

                <button
                    wire:click="save"
                    class="bg-green-600 hover:bg-green-700
                           text-white px-6 py-2 rounded-lg"
                >
                    登録
                </button>

            @endif

        </div>

    </div>

    {{-- 一覧カード --}}
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-lg font-semibold">
                📋 PC一覧
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100 text-left">

                        <th class="p-3 border-b">利用者</th>
                        <th class="p-3 border-b">品名</th>
                        <th class="p-3 border-b">ブランド</th>
                        <th class="p-3 border-b">CPU</th>
                        <th class="p-3 border-b">RAM</th>
                        <th class="p-3 border-b">ストレージ</th>
                        <th class="p-3 border-b">在庫</th>
                        <th class="p-3 border-b">単価</th>
                        <th class="p-3 border-b">操作</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($products as $product)

                    <tr class="hover:bg-gray-50">

                        <td class="p-3 border-b">
                            {{ $product->user?->name ?? '未割当' }}
                        </td>

                        <td class="p-3 border-b font-medium">
                            {{ $product->name }}
                        </td>

                        <td class="p-3 border-b">
                            {{ $product->brand }}
                        </td>

                        <td class="p-3 border-b">
                            {{ $product->cpu }}
                        </td>

                        <td class="p-3 border-b">
                            {{ $product->ram }}GB
                        </td>

                        <td class="p-3 border-b">
                            {{ $product->storage }}
                        </td>

                        <td class="p-3 border-b">
                            {{ $product->quantity }}
                        </td>

                        <td class="p-3 border-b">
                            ¥{{ number_format($product->unit_price) }}
                        </td>

                        <td class="p-3 border-b">

                            <div class="flex gap-2">

                                <button
                                    wire:click="edit({{ $product->id }})"
                                    class="bg-yellow-500 hover:bg-yellow-600
                                           text-white px-3 py-1 rounded"
                                >
                                    編集
                                </button>

                                <button
                                    wire:click="delete({{ $product->id }})"
                                    onclick="return confirm('削除しますか？')"
                                    class="bg-red-500 hover:bg-red-600
                                           text-white px-3 py-1 rounded"
                                >
                                    削除
                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="text-center p-8 text-gray-500"
                        >
                            データがありません
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- ページネーション --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>