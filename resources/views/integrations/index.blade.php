<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>連携管理</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold mb-8">連携管理（Cloud / Redmine / Slack）</h1>
        
        {{-- これだけが重要 --}}
        @livewire('integration-manager.integration-manager')
    </div>

    @livewireScripts
</body>
</html>