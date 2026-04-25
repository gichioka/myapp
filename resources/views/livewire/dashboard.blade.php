<div>
    <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard</h1>
        <p class="text-gray-500 mt-1">システム統計情報</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Users Card -->
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">ユーザー数</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
                <p class="text-xs text-gray-500 mt-2">登録ユーザー</p>
            </div>
        </div>

        <!-- Retired Users Card -->
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">退職者数</h3>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $retiredUsers }}</div>
                <p class="text-xs text-gray-500 mt-2">退職済みユーザー</p>
            </div>
        </div>

        <!-- Active Users Card -->
        <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">在職者数</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalUsers - $retiredUsers }}</div>
                <p class="text-xs text-gray-500 mt-2">現在勤務中</p>
            </div>
        </div>

        {{-- adminのみユーザー管理カードを表示 --}}
        @role('admin')
        <a href="/users" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 block">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">ユーザー管理</h3>
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">→</div>
                <p class="text-xs text-gray-500 mt-2">ユーザーの登録・編集・削除</p>
            </div>
        </a>
        @endrole

        {{-- ✅ 全員にツール管理カードを表示 --}}
        <a href="/tool-usages" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 block">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">ツール管理</h3>
                    <div class="p-2 bg-teal-100 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">→</div>
                <p class="text-xs text-gray-500 mt-2">誰がどのツールを使っているか管理</p>
            </div>
        </a>

        {{-- ✅ 連携管理カード --}}
        <a href="/integrations" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-xl hover:-translate-y-1 block">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">連携管理</h3>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900">→</div>
                <p class="text-xs text-gray-500 mt-2">Cloud・Redmine・Slackの連携設定</p>
            </div>
        </a>

    </div>
</div>