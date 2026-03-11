<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Saldo Wallet') }}</title>

    <!-- PWA & SEO Meta Tags Placeholder -->
    @yield('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb', // Blue 600
                        secondary: '#1e40af', // Blue 800
                        accent: '#3b82f6', // Blue 500
                    },
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 select-none" x-data="{ locked: false, pin: '', error: '', lastActivity: Date.now() }" x-init="
    const timeoutMs = 5 * 60 * 1000;
    const reset = () => { lastActivity = Date.now() };
    ['click','touchstart','keydown'].forEach(e => window.addEventListener(e, reset));
    setInterval(() => { if (Date.now() - lastActivity > timeoutMs) locked = true }, 10000);
">
    
    <!-- Desktop/Tablet Blocker Overlay -->
    <div class="hidden md:flex fixed inset-0 z-[100] bg-gray-900 text-white flex-col items-center justify-center p-8 text-center">
        <div class="bg-blue-600/20 p-6 rounded-full mb-8 animate-pulse">
            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
        </div>
        <h1 class="text-4xl font-extrabold mb-4 tracking-tight">Solo disponible en Móvil</h1>
        <p class="text-gray-400 max-w-md mb-8 text-lg leading-relaxed">
            Esta aplicación está diseñada exclusivamente para una mejor experiencia en dispositivos móviles. 
            <br><br>
            Por favor, abre el enlace en tu teléfono.
        </p>
        
        <div class="bg-white p-4 rounded-xl shadow-lg">
            <!-- QR Code Placeholder (Using an API for demo) -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url()->current() }}" alt="Scan to Open" class="w-32 h-32">
            <p class="text-gray-500 text-xs mt-2 font-bold uppercase tracking-wide">Escanear para abrir</p>
        </div>
    </div>

    <div class="md:hidden">
        {{ $slot }}
    </div>

    <div x-show="locked" x-cloak class="fixed inset-0 z-[120] bg-black/90 backdrop-blur flex items-center justify-center p-6" style="display:none;">
        <div class="bg-white w-full max-w-xs rounded-2xl p-6 text-center">
            <h3 class="text-lg font-bold mb-2">Wallet Locked</h3>
            <p class="text-sm text-gray-500 mb-4">Enter your 4-digit PIN to unlock</p>
            <input x-model="pin" type="password" maxlength="4" pattern="\\d{4}" class="w-full px-4 py-3 rounded-lg border border-gray-300 text-center tracking-[0.5em] text-xl" placeholder="••••">
            <p class="text-sm text-red-600 mt-2" x-text="error"></p>
            <button @click="async () => {
                error = '';
                try {
                    const res = await fetch('/verify-pin', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: JSON.stringify({ pin }) });
                    if (res.ok) { locked = false; pin = ''; lastActivity = Date.now(); }
                    else { const j = await res.json(); error = j.message || 'Invalid PIN'; }
                } catch { error = 'Error verifying PIN' }
            }" class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg font-semibold">Unlock</button>
        </div>
    </div>
</body>
</html>
