<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50">

<div class="flex h-screen">

    <div class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col shadow-lg">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                Admin
            </h1>
            <p class="text-gray-400 text-xs mt-1">管理画面</p>
        </div>

        <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
            <a href="/dashboard"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('dashboard') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m4 0l2.5-3M17 6h2V4m0 8l-4 3m-2 2l-4-3M3 12l2 3m0 0l4-3m2-2l4 3M3 12H1m18 0h2"></path>
                </svg>
                <span class="font-medium">ダッシュボード</span>
            </a>

            @role('admin')
            <a href="/users"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('users') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">ユーザー管理</span>
            </a>
            @endrole

            <a href="/tool-usages"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('tool-usages') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">ツール管理</span>
            </a>

            <a href="/integrations"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('integrations') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="font-medium">連携管理</span>
            </a>

            <a href="/products"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('products') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="font-medium">PC管理</span>
            </a>

            <a href="/new-employees"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('new-employees') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="font-medium">新入社員管理</span>
            </a>

            <a href="/server-accounts"
               class="block px-4 py-3 rounded-lg text-gray-100 hover:bg-gray-700 transition duration-200 flex items-center gap-3 
                      {{ request()->is('server-accounts') ? 'bg-blue-600' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="font-medium">サーバーアカウント管理</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-700">
            <p class="text-gray-400 text-xs">© 2026 Admin System</p>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="bg-white border-b border-gray-200 px-8 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm text-gray-600">
                    @if(request()->is('dashboard'))
                        ダッシュボード
                    @elseif(request()->is('users'))
                        ユーザー管理
                    @elseif(request()->is('tool-usages'))
                        ツール管理
                    @elseif(request()->is('integrations'))
                        連携管理
                    @elseif(request()->is('products'))
                        PC管理
                    @elseif(request()->is('new-employees'))
                        新入社員管理
                    @elseif(request()->is('server-accounts'))
                        サーバーアカウント管理
                    @else
                        管理画面
                    @endif
                </h2>
            </div>
        </div>

        <div class="flex-1 overflow-auto p-8">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
</body>
</html>