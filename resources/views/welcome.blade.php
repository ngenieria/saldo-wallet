@php($locale = $locale ?? request()->route('locale') ?? 'es-CO')
@php($seo = $seo ?? [])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] ?? ('Saldo · ' . __('hero.title_1') . ' ' . __('hero.title_2')) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="description" content="{{ $seo['description'] ?? __('hero.subtitle') }}">
    @if (!empty($seo['robots']))
        <meta name="robots" content="{{ $seo['robots'] }}">
    @endif
    @if (!empty($seo['keywords']))
        <meta name="keywords" content="{{ $seo['keywords'] }}">
    @endif
    <link rel="canonical" href="{{ url('/' . $locale) }}">
    @php($faviconVersion = app(\App\Services\SettingsService::class)->get('seo.favicon_version') ?? '1')
    <link rel="icon" href="/favicon.ico?v={{ $faviconVersion }}">
</head>
<body class="antialiased font-sans bg-gray-50">
    <div class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen">
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
                            Saldo<span class="text-gray-900">.</span>
                        </a>

                        <nav class="hidden lg:flex items-center gap-1">
                            <button @mouseenter="open='productos'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                {{ __('nav.products') }}
                            </button>
                            <button @mouseenter="open='seguridad'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                {{ __('nav.security') }}
                            </button>
                            <button @mouseenter="open='ayuda'" @mouseleave="open=null" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                {{ __('nav.help') }}
                            </button>
                            <a href="#nosotros" class="px-4 py-2 text-sm font-semibold text-gray-700 hover:text-gray-900 rounded-xl hover:bg-gray-50">
                                {{ __('nav.about') }}
                            </a>

                            <div class="relative" @mouseenter="open='productos'" @mouseleave="open=null">
                                <div x-cloak x-show="open==='productos'" class="absolute right-0 top-10 w-[720px] bg-white border border-gray-100 shadow-xl rounded-2xl p-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Wallet</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('features.item1.title') }}</a>
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('features.item2.title') }}</a>
                                                <a href="#features" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('features.item3.title') }}</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Servicios</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-black">Recargas</a>
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-black">Pagos</a>
                                                <a href="#servicios" class="block text-sm font-semibold text-gray-900 hover:text-black">Tienda</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Empieza</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="{{ route('register') }}" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('cta.create_account') }}</a>
                                                <a href="{{ route('login') }}" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('cta.sign_in') }}</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-black">Preguntas</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 bg-gray-900 rounded-2xl p-5 text-white flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-bold">Saldo Wallet en tu celular</div>
                                            <div class="text-xs opacity-90 mt-1">Crea tu cuenta y empieza a mover tu plata.</div>
                                        </div>
                                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-sm font-semibold">{{ __('hero.start') }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="relative" @mouseenter="open='seguridad'" @mouseleave="open=null">
                                <div x-cloak x-show="open==='seguridad'" class="absolute right-0 top-10 w-[720px] bg-white border border-gray-100 shadow-xl rounded-2xl p-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Protección</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-black">OTP</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-black">Dispositivos</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-black">Anti-fraude</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Consejos</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-black">Evita estafas</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-black">Cuida tu clave</a>
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-black">Reporta actividad</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Panel Admin</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="https://admin.saldo.com.co/login" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('cta.sign_in') }}</a>
                                                <a href="https://admin.saldo.com.co/security" class="block text-sm font-semibold text-gray-900 hover:text-black">Seguridad</a>
                                                <a href="https://admin.saldo.com.co/settings/integrations" class="block text-sm font-semibold text-gray-900 hover:text-black">Integraciones</a>
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
                                                <a href="#faq" class="block text-sm font-semibold text-gray-900 hover:text-black">Preguntas frecuentes</a>
                                                <a href="#contacto" class="block text-sm font-semibold text-gray-900 hover:text-black">Contáctanos</a>
                                                <a href="#seguridad" class="block text-sm font-semibold text-gray-900 hover:text-black">{{ __('nav.security') }}</a>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-400 uppercase">Comunidad</div>
                                            <div class="mt-3 space-y-2">
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-black">Noticias</a>
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-black">Trabaja con nosotros</a>
                                                <a href="#nosotros" class="block text-sm font-semibold text-gray-900 hover:text-black">Blog</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </nav>

                        <div class="hidden lg:flex items-center gap-3">
                            <div x-data="{ openLang: false }" class="relative">
                                <button type="button" @click="openLang = !openLang" class="px-3 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                    {{ $locale }}
                                </button>
                                <div x-cloak x-show="openLang" @click.away="openLang=false" class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                                    <a class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50" href="{{ url('/es-CO') }}">es-CO</a>
                                    <a class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50" href="{{ url('/es-AR') }}">es-AR</a>
                                    <a class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50" href="{{ url('/es-MX') }}">es-MX</a>
                                    <a class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50" href="{{ url('/es-ES') }}">es-ES</a>
                                    <a class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-50" href="{{ url('/en-US') }}">en-US</a>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">{{ __('cta.sign_in') }}</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-black">
                                {{ __('cta.create_account') }}
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
                                    <a href="#features" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">{{ __('nav.products') }}</a>
                                    <a href="#seguridad" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">{{ __('nav.security') }}</a>
                                    <a href="#faq" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">{{ __('nav.help') }}</a>
                                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-gray-800 hover:bg-gray-50">{{ __('cta.sign_in') }}</a>
                                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-white bg-gray-900 hover:bg-black">{{ __('cta.create_account') }}</a>
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
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-gray-900"></span>
                                {{ __('hero.badge') }}
                            </div>
                            <h1 class="mt-5 text-4xl sm:text-5xl font-black text-gray-900 leading-tight">
                                {{ __('hero.title_1') }}
                                <span class="text-gray-900">{{ __('hero.title_2') }}</span>
                            </h1>
                            <p class="mt-4 text-lg text-gray-600">
                                {{ __('hero.subtitle') }}
                            </p>
                            <div class="mt-7 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-gray-900 hover:bg-black text-white font-semibold text-center">
                                    {{ __('cta.create_account') }}
                                </a>
                                <a href="{{ route('login') }}" class="px-5 py-3 rounded-2xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-900 font-semibold text-center">
                                    {{ __('cta.sign_in') }}
                                </a>
                            </div>
                            <div class="mt-6 flex items-center gap-3 text-xs text-gray-500">
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">OTP por SMS/Email</div>
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">Admin con seguridad</div>
                                <div class="px-3 py-2 rounded-2xl bg-white border border-gray-200">Multi-moneda</div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -inset-6 bg-gradient-to-br from-gray-200/60 via-white/60 to-gray-300/60 blur-3xl rounded-full"></div>
                            <div class="relative overflow-hidden rounded-3xl border border-gray-100 shadow-2xl bg-gradient-to-br from-[#0b0b0d] to-[#1b1b20] text-white">
                                <div class="p-7">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-semibold">
                                        {{ __('banner.kicker') }}
                                    </div>
                                    <div class="mt-4 text-3xl sm:text-4xl font-black leading-tight">
                                        {{ __('banner.title') }}
                                    </div>
                                    <div class="mt-3 text-sm text-white/80">
                                        {{ __('banner.subtitle') }}
                                    </div>
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                        <a href="#faq" class="px-5 py-3 rounded-2xl bg-white text-gray-900 hover:bg-gray-100 font-semibold text-center">
                                            {{ __('banner.cta_primary') }}
                                        </a>
                                        <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/15 text-white font-semibold text-center">
                                            {{ __('banner.cta_secondary') }}
                                        </a>
                                    </div>

                                    <div class="mt-7 grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                                            <div class="text-xs text-white/70">Tips de seguridad</div>
                                            <div class="mt-1 text-lg font-black">Anti-fraude</div>
                                        </div>
                                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                                            <div class="text-xs text-white/70">Soporte</div>
                                            <div class="mt-1 text-lg font-black">24/7</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="absolute -right-10 -bottom-12 w-60 h-60 rounded-full bg-white/10 blur-2xl"></div>
                                <div class="absolute right-6 bottom-6 w-32 h-32 rounded-3xl bg-white/10 border border-white/10 rotate-12"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="features" class="py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-end justify-between gap-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">{{ __('features.title') }}</h2>
                            <p class="mt-2 text-gray-600">{{ __('features.subtitle') }}</p>
                        </div>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-sm font-semibold text-gray-900">
                            {{ __('hero.start') }}
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">{{ __('features.item1.title') }}</div>
                            <div class="mt-2 text-sm text-gray-600">{{ __('features.item1.body') }}</div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">{{ __('features.item2.title') }}</div>
                            <div class="mt-2 text-sm text-gray-600">{{ __('features.item2.body') }}</div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-lg font-bold text-gray-900">{{ __('features.item3.title') }}</div>
                            <div class="mt-2 text-sm text-gray-600">{{ __('features.item3.body') }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="seguridad" class="py-16 lg:py-20 bg-white border-y border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-black text-gray-900">{{ __('security.title') }}</h2>
                            <p class="mt-3 text-gray-600">{{ __('security.subtitle') }}</p>
                            <div class="mt-6 space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ __('security.bullet1') }}</div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ __('security.bullet2') }}</div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="mt-1 w-6 h-6 rounded-full bg-gray-900 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="text-sm text-gray-700">{{ __('security.bullet3') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-gray-900 to-black rounded-3xl p-8 text-white shadow-2xl">
                            <div class="text-sm font-semibold opacity-90">Tip de seguridad</div>
                            <div class="mt-2 text-2xl font-black leading-tight">Nunca compartas tu código OTP.</div>
                            <div class="mt-3 text-sm opacity-90">Saldo nunca te pedirá el código por llamadas o redes sociales.</div>
                            <div class="mt-6 flex gap-3">
                                <a href="#faq" class="px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-sm font-semibold">Ver consejos</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-white text-gray-900 hover:bg-gray-50 text-sm font-semibold">{{ __('cta.create_account') }}</a>
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
                            <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-gray-900 hover:bg-black text-white font-semibold text-center">{{ __('cta.create_account') }}</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-[#14061f] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="flex items-center justify-between gap-6">
                    <div class="text-2xl font-black">
                        Saldo<span class="text-white">.</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="#" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold">
                            <span class="w-5 h-5 rounded-lg bg-white/15 flex items-center justify-center"></span>
                            App Store
                        </a>
                        <a href="#" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold">
                            <span class="w-5 h-5 rounded-lg bg-white/15 flex items-center justify-center">▶</span>
                            Google Play
                        </a>
                        <a href="#" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold">
                            <span class="w-5 h-5 rounded-lg bg-white/15 flex items-center justify-center">A</span>
                            AppGallery
                        </a>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10">
                    <div>
                        <div class="text-sm font-semibold text-white/90">Información legal</div>
                        <div class="mt-4 space-y-2 text-sm text-white/70">
                            <a href="{{ url('/' . ($locale ?? 'es-CO') . '/terminos') }}" class="block hover:text-white">Términos y condiciones</a>
                            <a href="{{ url('/' . ($locale ?? 'es-CO') . '/privacidad') }}" class="block hover:text-white">Política de datos</a>
                            <a href="#" class="block hover:text-white">Política de cookies</a>
                            <a href="#" class="block hover:text-white">Defensor del consumidor</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white/90">Para personas</div>
                        <div class="mt-4 space-y-2 text-sm text-white/70">
                            <a href="#features" class="block hover:text-white">Bolsillos</a>
                            <a href="#features" class="block hover:text-white">Transferencias</a>
                            <a href="#servicios" class="block hover:text-white">Pagos</a>
                            <a href="#servicios" class="block hover:text-white">Recargas</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white/90">Para negocio</div>
                        <div class="mt-4 space-y-2 text-sm text-white/70">
                            <a href="#servicios" class="block hover:text-white">Cobros</a>
                            <a href="#servicios" class="block hover:text-white">QR para vender</a>
                            <a href="#servicios" class="block hover:text-white">Tienda</a>
                            <a href="#features" class="block hover:text-white">Reportes</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white/90">Ayuda</div>
                        <div class="mt-4 space-y-2 text-sm text-white/70">
                            <a href="#faq" class="block hover:text-white">Centro de ayuda</a>
                            <a href="#seguridad" class="block hover:text-white">Tips de seguridad</a>
                            <a href="mailto:support@saldo.com.co" class="block hover:text-white">Soporte</a>
                            <a href="{{ route('login') }}" class="block hover:text-white">Ingresar</a>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-white/90">Conócenos</div>
                        <div class="mt-4 space-y-2 text-sm text-white/70">
                            <a href="#nosotros" class="block hover:text-white">¿Quiénes somos?</a>
                            <a href="#nosotros" class="block hover:text-white">Blog</a>
                            <a href="#nosotros" class="block hover:text-white">Trabaja con nosotros</a>
                            <a href="#contacto" class="block hover:text-white">Contacto</a>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-white/60">© {{ date('Y') }} Saldo Wallet. Todos los derechos reservados.</div>
                    <div class="flex items-center gap-3">
                        <a href="https://facebook.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center text-xs font-semibold">f</a>
                        <a href="https://instagram.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center text-xs font-semibold">ig</a>
                        <a href="https://x.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center text-xs font-semibold">x</a>
                        <a href="https://youtube.com" target="_blank" rel="noreferrer" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/15 flex items-center justify-center text-xs font-semibold">yt</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <x-chatbot />
</body>
</html>
