<x-layouts.guest>
    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 hover:bg-white">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="text-xs text-gray-400">English</div>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Let’s Sign You In!</h1>
            <p class="text-sm text-gray-500 mt-1">Enter your email/phone and password.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="identifier">Email / Phone</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('identifier') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-8 0m12 8H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                    <input id="identifier" class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" type="text" name="identifier" value="{{ old('identifier') }}" required autofocus placeholder="email or +57..." />
                </div>
                @error('identifier')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="password">Password</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm6 0a6 6 0 10-12 0v3h12v-3z" />
                    </svg>
                    <input id="password" class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" type="password" name="password" required autocomplete="current-password" placeholder="Your password" />
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 active:bg-blue-800 transition">
                Continue
            </button>

            <div class="text-center text-sm text-gray-500">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign up</a>
            </div>
        </form>
    </div>
</x-layouts.guest>
