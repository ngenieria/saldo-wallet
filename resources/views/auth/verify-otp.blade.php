<x-layouts.guest>
    <div class="mb-4 text-sm text-gray-600">
        @if ($method === 'totp')
            {{ __('Ingresa el código de tu autenticador.') }}
        @else
            {{ __('Ingresa el código que enviamos por SMS a ') }} {{ $maskedPhone ?? __('tu número') }}.
        @endif
    </div>

    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="block font-medium text-sm text-gray-700">Código (6 dígitos)</label>
            <input id="code" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 border text-center tracking-[0.5em]" type="text" name="code" inputmode="numeric" maxlength="6" pattern="\d{6}" required autofocus />
            @error('code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Verificar') }}
            </button>
        </div>
    </form>

    <div class="flex items-center justify-between mt-4">
        @if ($method !== 'totp')
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('Reenviar código') }}
                </button>
            </form>
        @else
            <span class="text-sm text-gray-500">{{ __('Abre Google Authenticator u otra app.') }}</span>
        @endif

        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('login') }}">
            {{ __('Volver') }}
        </a>
    </div>
</x-layouts.guest>
