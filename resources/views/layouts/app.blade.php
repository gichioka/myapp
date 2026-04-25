<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-gray-100">

<div class="min-h-screen">

{{ $slot }}

</div>

@livewireScripts

</body>
</html>