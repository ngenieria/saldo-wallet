<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Saldo Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center font-bold text-xl border-b border-gray-700">
                Saldo Admin
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Users</a>
                <a href="{{ route('admin.kyc') }}" class="block px-4 py-2 rounded hover:bg-gray-700">KYC Requests</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Transactions</a>
                <a href="{{ route('admin.security') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Security</a>
                <a href="{{ route('admin.settings.security') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Settings</a>
                <a href="{{ route('admin.settings.integrations') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Integrations</a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700 rounded">Logout</button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow h-16 flex items-center px-6">
                <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
            </header>
            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
