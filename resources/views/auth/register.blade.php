<x-layouts.guest>
    <div class="w-full">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 hover:bg-white">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="text-xs text-gray-400">English</div>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Let’s Sign You Up!</h1>
            <p class="text-sm text-gray-500 mt-1">Create your account to access your wallet.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="name">Full name</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" placeholder="Your full name" />
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="email">Email</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-8 0m12 8H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" placeholder="you@email.com" />
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Phone number</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('mobile_phone') || $errors->has('country_code') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <select name="country_code" class="bg-transparent outline-none text-gray-700 text-sm">
                        <option value="CO" {{ old('country_code', 'CO') === 'CO' ? 'selected' : '' }}>CO</option>
                        <option value="VE" {{ old('country_code') === 'VE' ? 'selected' : '' }}>VE</option>
                        <option value="US" {{ old('country_code') === 'US' ? 'selected' : '' }}>US</option>
                        <option value="MX" {{ old('country_code') === 'MX' ? 'selected' : '' }}>MX</option>
                    </select>
                    <div class="w-px h-6 bg-gray-200"></div>
                    <input name="mobile_phone" type="text" value="{{ old('mobile_phone') }}" required class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" placeholder="+57 300 000 0000" />
                </div>
                @error('country_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('mobile_phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="password">Password</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm6 0a6 6 0 10-12 0v3h12v-3z" />
                    </svg>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" placeholder="Create a strong password" />
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="password_confirmation">Confirm password</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm6 0a6 6 0 10-12 0v3h12v-3z" />
                    </svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400" placeholder="Repeat your password" />
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block" for="security_pin">Security PIN</label>
                <div class="flex items-center gap-3 px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('security_pin') ? 'border-red-300' : 'border-gray-200' }} focus-within:bg-white focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-500 transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-10a4 4 0 00-4 4v2h8v-2a4 4 0 00-4-4z" />
                    </svg>
                    <input id="security_pin" name="security_pin" type="password" maxlength="4" pattern="\d{4}" inputmode="numeric" required class="w-full bg-transparent outline-none text-gray-900 placeholder:text-gray-400 tracking-[0.5em]" placeholder="••••" />
                </div>
                @error('security_pin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 active:bg-blue-800 transition">
                Get a Code
            </button>

            <div class="text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Sign in</a>
            </div>
        </form>
    </div>
</x-layouts.guest>
