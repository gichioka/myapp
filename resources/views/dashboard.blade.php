<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-4">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">ダッシュボード</h1>
        <p class="text-xl text-gray-600">ようこそ、{{ auth()->user()->name ?? 'ユーザー' }}さん</p>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('users') }}" class="bg-white p-8 rounded-2xl shadow hover:shadow-md">
                👥 ユーザー管理
            </a>
            <a href="{{ route('tool-usages') }}" class="bg-white p-8 rounded-2xl shadow hover:shadow-md">
                🛠️ ツール利用状況
            </a>
            <a href="{{ route('integrations.index') }}" class="bg-white p-8 rounded-2xl shadow hover:shadow-md">
                ☁️ 連携管理
            </a>
        </div>
    </div>
</body>
</html>