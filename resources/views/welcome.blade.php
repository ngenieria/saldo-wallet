<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saldo Wallet - Global Digital Wallet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="description" content="Send, receive, and exchange money globally with Saldo Wallet. Secure, fast, and easy.">
</head>
<body class="antialiased font-sans bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold text-blue-600">Saldo.</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-gray-900">Features</a>
                    <a href="#security" class="text-gray-600 hover:text-gray-900">Security</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Log in</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Create Account</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                    Global Money <br>
                    <span class="text-blue-600">Without Borders</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Send, receive, and exchange money instantly. Supports USD, COP, EUR, and more.
                    Secure, fast, and built for everyone.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-blue-700 transition shadow-lg">Get Started</a>
                    <a href="#features" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-lg text-lg font-medium hover:bg-gray-200 transition">Learn More</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                <img src="https://via.placeholder.com/600x400?text=App+Preview" alt="App Preview" class="rounded-xl shadow-2xl">
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Everything you need in one wallet</h2>
                <p class="mt-4 text-xl text-gray-600">Manage your finances globally with ease.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Currency</h3>
                    <p class="text-gray-600">Hold and exchange USD, COP, EUR, MXN, ARS, BRL instantly with competitive rates.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Instant Transfers</h3>
                    <p class="text-gray-600">Send money to anyone using email, phone number, or QR code instantly.</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">QR Payments</h3>
                    <p class="text-gray-600">Pay or get paid simply by scanning a unique QR code. Contactless and fast.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security -->
    <section id="security" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0 order-2 md:order-1">
                <img src="https://via.placeholder.com/500x500?text=Security+Shield" alt="Security" class="rounded-xl">
            </div>
            <div class="md:w-1/2 md:pl-16 order-1 md:order-2">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Bank-Grade Security</h2>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-lg text-gray-700">Identity Verification (KYC) with facial recognition.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-lg text-gray-700">4-Digit Security PIN for all transactions.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-lg text-gray-700">Advanced encryption and fraud detection.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-2xl font-bold mb-4">Saldo.</h3>
                <p class="text-gray-400">The global fintech wallet for everyone.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Wallet</a></li>
                    <li><a href="#" class="hover:text-white">Exchange</a></li>
                    <li><a href="#" class="hover:text-white">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Legal</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact</h4>
                <p class="text-gray-400">support@saldo.com.co</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-center text-gray-500">
            &copy; {{ date('Y') }} Saldo Wallet. All rights reserved.
        </div>
    </footer>
</body>
</html>
