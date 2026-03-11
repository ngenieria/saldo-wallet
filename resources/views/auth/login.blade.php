<x-layouts.guest>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please log in to your account.') }}
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email or Mobile -->
        <div class="mb-4">
            <label for="identifier" class="block font-medium text-sm text-gray-700">Email or Mobile</label>
            <input id="identifier" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 border" type="text" name="identifier" value="{{ old('identifier') }}" required autofocus />
            @error('identifier')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 border" type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Security PIN -->
        <div class="mb-4">
            <label for="pin" class="block font-medium text-sm text-gray-700">4-Digit Security PIN</label>
            <input id="pin" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 border" type="password" name="pin" maxlength="4" pattern="\d{4}" required />
            @error('pin')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('register') }}">
                {{ __('Need an account?') }}
            </a>

            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-layouts.guest>
