@php($locale = request()->route('locale') ?? 'es-CO')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saldo Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="description" content="Saldo Wallet: mueve tu plata desde el celu. Envía, recibe y administra tu dinero con seguridad.">
</head>
<body class="antialiased font-sans bg-gray-50">
    <div class="bg-gradient-to-br from-emerald-50 via-white to-sky-50 min-h-screen">
        <div class="bg-white/70 backdrop-blur border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-10 flex items-center justify-between">
                <div class="text-xs text-gray-500">Soporte: support@saldo.com.co</div>
                <div class="flex items-center gap-3 text-xs">
                    <a class="text-gray-600 hover:text-gray-900" href="https://facebook.com" target="_blank" rel="noreferrer">Facebook</a>
                    <a class="text-gray-600 hover:text-gray-900" href="https://instagram.com" target="_blank" rel="noreferrer">Instagram</a>
                    <a class="text-gray-600 hover:text-gray-900" href="https://x.com" target="_blank" rel="noreferrer">X</a>
                    <a class="text-gray-600 hover:text-gray-900" href="https://youtube.com" target="_blank" rel="noreferrer">YouTube</a>
                </div>
            </div>
        </div>

        <header class="sticky top-0 z-50">
            <div class="bg-white/80 backdrop-blur border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div x-data="{ open: null }" class="h-16 flex items-center justify-between">
                        <a href="{{ route('home') }}" class="text-2xl font-black text-gray-900 tracking-tight">
                            Saldo<span class="text-emerald-600">.</span>
                        </a>

                        <nav class="hidden lg:flex items-center gap-1">
                            <button @mouseenter="open='productos'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                Productos
                            </button>
                            <button @mouseenter="open='seguridad'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                Seguridad
                            </button>
                            <button @mouseenter="open='ayuda'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                Ayuda
                            </button>
                            <a href="#nosotros" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                Nosotros
                            </a>

                            <div class="relative" @mouseenter="open='productos'" @mouseleave="open=null">
                                <div x-cloak x-show="open==='productos'" class="absolute right-0 top-10 w-[720px] bg-white border border-gray-100 shadow-xl rounded-2xl p-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Wallet</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Bolsillos</a>
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Transferencias</a>
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Pagos con QR</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Servicios</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Recargas</a>
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Paga facturas</a>
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Tienda</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Empieza</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="{{ route('register') }}" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Crear cuenta</a>
                                                <a href="{{ route('login') }}" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Ingresar</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Preguntas</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 bg-gradient-to-r from-emerald-600 to-sky-600 rounded-2xl p-5 text-white flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-bold">Saldo Wallet en tu celular</div>
                                            <div class="text-xs opacity-90 mt-1">Crea tu cuenta y empieza a mover tu plata.</div>
                                        </div>
                                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-sm font-semibold">Empezar</a>
                                    </div>
                                </div>
                            </div>

                            <div class="relative" @mouseenter="open='seguridad'" @mouseleave="open=null">
                                <div x-cloak x-show="open==='seguridad'" class="absolute right-0 top-10 w-[720px] bg-white border border-gray-100 shadow-xl rounded-2xl p-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Protección</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">OTP por SMS y Email</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Dispositivos confiables</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Monitoreo antifraude</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Consejos</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Evita estafas</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Cuida tu clave</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Reporta actividad</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Panel Admin</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="https://admin.saldo.com.co/login" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Ingresar</a>
                                                <a href="https://admin.saldo.com.co/security" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Dashboard seguridad</a>
                                                <a href="https://admin.saldo.com.co/settings/integrations" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">SMTP/Twilio</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative" @mouseenter="open='ayuda'" @mouseleave="open=null">
                                <div x-cloak x-show="open==='ayuda'" class="absolute right-0 top-10 w-[520px] bg-white border border-gray-100 shadow-xl rounded-2xl p-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Soporte</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Preguntas frecuentes</a>
                                                <a href="#contacto" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Contáctanos</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Seguridad</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Comunidad</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Noticias</a>
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Trabaja con nosotros</a>
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-emerald-700">Blog</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>

                        <div class="hidden lg:flex items-center gap-3">
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">Ingresar</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700">
                                Crear cuenta
                            </a>
                        </div>

                        <div class="lg:hidden" x-data="{ open: false }">
                            <button @click="open=!open" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 border border-gray-200">
                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div x-cloak x-show="open" class="absolute left-0 right-0 top-[104px] bg-white border-b border-gray-100 shadow-lg">
                                <div class="max-w-7xl mx-auto px-4 py-4 space-y-2">
                                    <a href="#features" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">Productos</a>
                                    <a href="#seguridad" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">Seguridad</a>
                                    <a href="#faq" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">Ayuda</a>
                                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">Ingresar</a>
                                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700">Crear cuenta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="pt-16 lg:pt-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Tu wallet digital
                            </div>
                            <h1 class="mt-5 text-4xl sm:text-5xl font-black text-gray-900 leading-tight">
                                Mueve tu plata
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-sky-600">desde el celu</span>
                            </h1>
                            <p class="mt-4 text-lg text-gray-600">
                                Envía, recibe, paga y administra tu dinero con seguridad. Diseñado para una experiencia rápida y simple.
                            </p>
                            <div class="mt-7 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-center">
                                    Crear cuenta
                                </a>
                                <a href="{{ route('login') }}" class="px-5 py-3 rounded-2xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-900 font-semibold text-center">
                                    Ingresar
                                </a>
                            </div>
                            <div class="mt-6 flex items-center gap-3 text-xs text-gray-500">
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">OTP por SMS/Email</div>
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">Admin con seguridad</div>
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">Multi-moneda</div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -inset-4 bg-gradient-to-br from-emerald-200/40 via-sky-200/30 to-indigo-200/30 blur-2xl rounded-full"></div>
                            <div class="relative bg-white border border-gray-100 rounded-3xl shadow-2xl overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                                    <div class="text-sm font-semibold text-gray-900">Saldo App Preview</div>
                                    <div class="text-xs text-gray-400">Mobile UI</div>
                                </div>
                                <div class="p-6">
                                    <div class="h-52 rounded-2xl bg-gradient-to-br from-emerald-600 to-sky-600"></div>
                                    <div class="mt-5 grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                            <div class="text-xs text-gray-500">Disponible</div>
                                            <div class="text-lg font-black text-gray-900 mt-1">$ 0</div>
                                        </div>
                                        <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                                            <div class="text-xs text-gray-500">Movimientos</div>
                                            <div class="text-lg font-black text-gray-900 mt-1">+</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-end justify-between gap-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Todo en una sola wallet</h2>
                            <p class="mt-2 text-gray-600">Herramientas para tu día a día, en una experiencia pensada para móvil.</p>
                        </div>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-sm font-semibold text-gray-900">
                            Empezar
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">Multi-moneda</div>
                            <div class="mt-2 text-sm text-gray-600">Administra balances en varias monedas y mueve tu dinero con facilidad.</div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">Transferencias</div>
                            <div class="mt-2 text-sm text-gray-600">Envía y recibe usando email, teléfono o QR, con trazabilidad.</div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">Pagos con QR</div>
                            <div class="mt-2 text-sm text-gray-600">Paga y cobra sin efectivo con una experiencia simple y rápida.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="seguridad" class="py-16 lg:py-20 bg-white border-y border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Seguridad primero</h2>
                            <p class="mt-3 text-gray-600">Controles diseñados para reducir fraude y proteger tu acceso.</p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700"><span class="font-semibold">OTP por SMS y Email</span> con tiempo de expiración y límites de reenvío.</div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700"><span class="font-semibold">Dispositivos</span> con verificación y registro de actividad.</div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-emerald-600 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700"><span class="font-semibold">Panel admin</span> con auditoría, allowlist y configuración de integraciones.</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-emerald-600 to-sky-600 rounded-3xl p-8 text-white shadow-2xl">
                            <div class="text-sm font-semibold opacity-90">Tip de seguridad</div>
                            <div class="mt-2 text-2xl font-black leading-tight">Nunca compartas tu código OTP.</div>
                            <div class="mt-3 text-sm opacity-90">Saldo nunca te pedirá el código por llamadas o redes sociales.</div>
                            <div class="mt-6 flex gap-3">
                                <a href="#faq" class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-sm font-semibold">Ver consejos</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-white text-gray-900 hover:bg-gray-50 text-sm font-semibold">Crear cuenta</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="servicios" class="py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Servicios</h2>
                    <p class="mt-2 text-gray-600">Pagos, recargas y herramientas para tu negocio.</p>
                    <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="text-lg font-bold text-gray-900">Recargas</div>
                            <div class="mt-2 text-sm text-gray-600">Recarga tu celular en segundos desde tu wallet.</div>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="text-lg font-bold text-gray-900">Pagos</div>
                            <div class="mt-2 text-sm text-gray-600">Paga con QR y recibe confirmación inmediata.</div>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="text-lg font-bold text-gray-900">Tienda</div>
                            <div class="mt-2 text-sm text-gray-600">Herramientas para vender online y dar visibilidad.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="faq" class="py-16 lg:py-20 bg-white border-y border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-end justify-between gap-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">Preguntas frecuentes</h2>
                            <p class="mt-2 text-gray-600">Resolvemos dudas comunes para que uses Saldo con confianza.</p>
                        </div>
                    </div>

                    <div x-data="{ open: 1 }" class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <button @click="open = open === 1 ? null : 1" class="text-left bg-gray-50 border border-gray-200 rounded-3xl p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-gray-900">¿Cómo inicio sesión?</div>
                                <div class="text-gray-400" x-text="open === 1 ? '–' : '+'"></div>
                            </div>
                            <div x-cloak x-show="open === 1" class="mt-3 text-sm text-gray-600">
                                Ingresas tu correo o teléfono y tu password. Luego confirmas con un código OTP de 4 dígitos enviado por SMS y Email.
                            </div>
                        </button>

                        <button @click="open = open === 2 ? null : 2" class="text-left bg-gray-50 border border-gray-200 rounded-3xl p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-gray-900">¿Qué hago si no llega el código?</div>
                                <div class="text-gray-400" x-text="open === 2 ? '–' : '+'"></div>
                            </div>
                            <div x-cloak x-show="open === 2" class="mt-3 text-sm text-gray-600">
                                Usa “Reenviar código”. Si persiste, verifica que tu teléfono y email estén correctos.
                            </div>
                        </button>

                        <button @click="open = open === 3 ? null : 3" class="text-left bg-gray-50 border border-gray-200 rounded-3xl p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-gray-900">¿Saldo funciona en computador?</div>
                                <div class="text-gray-400" x-text="open === 3 ? '–' : '+'"></div>
                            </div>
                            <div x-cloak x-show="open === 3" class="mt-3 text-sm text-gray-600">
                                Por seguridad, el Wallet está habilitado solo para teléfonos móviles.
                            </div>
                        </button>

                        <button @click="open = open === 4 ? null : 4" class="text-left bg-gray-50 border border-gray-200 rounded-3xl p-5">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-gray-900">¿Dónde configuro integraciones?</div>
                                <div class="text-gray-400" x-text="open === 4 ? '–' : '+'"></div>
                            </div>
                            <div x-cloak x-show="open === 4" class="mt-3 text-sm text-gray-600">
                                En el panel admin puedes configurar SMTP y Twilio, y probar los envíos.
                            </div>
                        </button>
                    </div>
                </div>
            </section>

            <section id="contacto" class="py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <div>
                            <div class="text-2xl font-black text-gray-900">¿Necesitas ayuda?</div>
                            <div class="mt-2 text-gray-600">Escríbenos y te acompañamos en la solución.</div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                            <a href="mailto:support@saldo.com.co" class="px-5 py-3 rounded-2xl bg-gray-900 hover:bg-black text-white font-semibold text-center">Contactar soporte</a>
                            <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-center">Crear cuenta</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-gray-950 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10">
                    <div class="lg:col-span-2">
                        <div class="text-2xl font-black">Saldo<span class="text-emerald-400">.</span></div>
                        <div class="mt-3 text-sm text-gray-300 max-w-md">
                            Una experiencia enfocada en móvil para mover tu plata con seguridad.
                        </div>
                        <div class="mt-6 flex items-center gap-3">
                            <a href="https://facebook.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                                <span class="text-sm font-semibold">f</span>
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                                <span class="text-sm font-semibold">ig</span>
                            </a>
                            <a href="https://x.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                                <span class="text-sm font-semibold">x</span>
                            </a>
                            <a href="https://youtube.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center">
                                <span class="text-sm font-semibold">yt</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white">Productos</div>
                        <div class="mt-4 space-y-2 text-sm text-gray-300">
                            <a href="#features" class="block hover:text-white">Wallet</a>
                            <a href="#servicios" class="block hover:text-white">Servicios</a>
                            <a href="#seguridad" class="block hover:text-white">Seguridad</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white">Ayuda</div>
                        <div class="mt-4 space-y-2 text-sm text-gray-300">
                            <a href="#faq" class="block hover:text-white">Preguntas</a>
                            <a href="#contacto" class="block hover:text-white">Soporte</a>
                            <a href="mailto:support@saldo.com.co" class="block hover:text-white">Email</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white">Legal</div>
                        <div class="mt-4 space-y-2 text-sm text-gray-300">
                            <a href="{{ url('/' . ($locale ?? 'es-CO') . '/terminos') }}" class="block hover:text-white">Términos</a>
                            <a href="{{ url('/' . ($locale ?? 'es-CO') . '/privacidad') }}" class="block hover:text-white">Privacidad</a>
                            <a href="#" class="block hover:text-white">Cookies</a>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs text-gray-400">© {{ date('Y') }} Saldo Wallet. Todos los derechos reservados.</div>
                    <div class="text-xs text-gray-400">Hecho para móvil.</div>
                </div>
            </div>
        </footer>
    </div>

    <x-chatbot />
</body>
</html>
