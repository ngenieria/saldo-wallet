<x-layouts.guest>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Ingresa el código 2FA de tu autenticador.') }}
    </div>

    <form method="POST" action="{{ route('admin.verify.post') }}">
        @csrf

        <div class="mb-4">
            <label for="code" class="block font-medium text-sm text-gray-700">Código (6 dígitos)</label>
            <input id="code" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2 border text-center tracking-[0.5em]" type="text" name="code" inputmode="numeric" maxlength="6" pattern="\d{6}" required autofocus />
            @error('code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('admin.login') }}">
                {{ __('Volver') }}
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Verificar') }}
            </button>
        </div>
    </form>
</x-layouts.guest>

