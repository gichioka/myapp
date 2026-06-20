<div>
    <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Dashboard
        </h1>
        <p class="text-gray-500 mt-1">システム統計情報</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- ユーザー数 --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">ユーザー数</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">👥</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $totalUsers }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    登録ユーザー
                </p>
            </div>
        </div>

        {{-- 退職者 --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">退職者数</h3>
                    <div class="p-2 bg-orange-100 rounded-lg">🚪</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $retiredUsers }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    退職済みユーザー
                </p>
            </div>
        </div>

        {{-- 在職者 --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">在職者数</h3>
                    <div class="p-2 bg-green-100 rounded-lg">✅</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $totalUsers - $retiredUsers }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    現在勤務中
                </p>
            </div>
        </div>

        {{-- PC総台数 --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">PC総台数</h3>
                    <div class="p-2 bg-cyan-100 rounded-lg">💻</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $totalProducts }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    登録PC
                </p>
            </div>
        </div>

        {{-- 利用中PC --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">利用中PC</h3>
                    <div class="p-2 bg-indigo-100 rounded-lg">👤</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $assignedProducts }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    割当済み
                </p>
            </div>
        </div>

        {{-- 未割当PC --}}
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">未割当PC</h3>
                    <div class="p-2 bg-yellow-100 rounded-lg">📦</div>
                </div>

                <div class="text-3xl font-bold text-gray-900">
                    {{ $unassignedProducts }}
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    在庫・未配布
                </p>
            </div>
        </div>
        {{-- 入社予定者 --}}
<div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100"></div>

    <div class="relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                入社予定者
            </h3>
            <div class="p-2 bg-purple-100 rounded-lg">🧑‍💼</div>
        </div>

        <div class="text-3xl font-bold text-gray-900">
            {{ $newEmployees }}
        </div>

        <p class="text-xs text-gray-500 mt-2">
            登録中の入社予定者
        </p>
    </div>
</div>

{{-- 入社予定 --}}
<div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100"></div>

    <div class="relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                入社予定
            </h3>
            <div class="p-2 bg-blue-100 rounded-lg">📅</div>
        </div>

        <div class="text-3xl font-bold text-gray-900">
            {{ $scheduledEmployees }}
        </div>
    </div>
</div>

{{-- 入社済 --}}
<div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100"></div>

    <div class="relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                入社済
            </h3>
            <div class="p-2 bg-green-100 rounded-lg">✅</div>
        </div>

        <div class="text-3xl font-bold text-gray-900">
            {{ $joinedEmployees }}
        </div>
    </div>
</div>

{{-- 辞退 --}}
<div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
    <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100"></div>

    <div class="relative">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">
                辞退
            </h3>
            <div class="p-2 bg-red-100 rounded-lg">❌</div>
        </div>

        <div class="text-3xl font-bold text-gray-900">
            {{ $declinedEmployees }}
        </div>
    </div>
</div>
                {{-- ユーザー管理 --}}
        <a href="/users" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">
            <div class="relative">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">ユーザー管理</h3>
                <div class="text-3xl">→</div>
            </div>
        </a>

        {{-- ツール管理 --}}
        <a href="/tool-usages" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">ツール管理</h3>
            <div class="text-3xl">→</div>
        </a>

        {{-- 連携管理 --}}
        <a href="/integrations" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">連携管理</h3>
            <div class="text-3xl">→</div>
        </a>

        {{-- サーバー管理 --}}
        <a href="/server-accounts" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">サーバー管理</h3>
            <div class="text-3xl">→</div>
        </a>

        {{-- PC資産管理 --}}
        <a href="{{ route('products') }}"
           class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">

            <h3 class="text-sm font-semibold text-gray-600 mb-2">
                PC資産管理
            </h3>

            <div class="text-3xl">💻</div>

            <p class="text-xs text-gray-500 mt-2">
                PCの管理画面へ
            </p>

        </a>

        {{-- 入社予定者管理 --}}
        <a href="{{ route('new-employees') }}"
           class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">

            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">
                    入社予定者管理
                </h3>

                <div class="text-3xl">🧑‍💼</div>

                <p class="text-xs text-gray-500 mt-2">
                    入社予定者の管理画面へ
                </p>
            </div>
            
{{-- 退職者管理 --}}
        <a href="{{ route('retirements') }}"
           class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md hover:shadow-xl hover:-translate-y-1 transition block">

            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100"></div>

            <div class="relative">
                <h3 class="text-sm font-semibold text-gray-600 mb-2">
                    退職者管理
                </h3>

                <div class="text-3xl">🚪</div>

                <p class="text-xs text-gray-500 mt-2">
                    退職者の管理画面へ
                </p>
            </div>
         </a>
        </a>

    </div>
</div>