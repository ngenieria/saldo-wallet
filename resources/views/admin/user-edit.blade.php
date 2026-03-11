<x-layouts.admin>
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div class="text-lg font-semibold text-gray-900">Editar usuario</div>
                <a href="{{ route('admin.users.show', $user->id) }}" class="text-sm text-gray-600 hover:text-gray-900">Volver</a>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="name">Nombre</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                    <input id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mobile_phone">Teléfono</label>
                    <input id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone', $user->mobile_phone) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('mobile_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="country_code">País (ISO2)</label>
                    <input id="country_code" name="country_code" value="{{ old('country_code', $user->country_code) }}" maxlength="2" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('country_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="kyc_status">KYC</label>
                    <select id="kyc_status" name="kyc_status" class="mt-1 w-full rounded border-gray-300 p-2 border">
                        @foreach (['pending','approved','rejected'] as $s)
                            <option value="{{ $s }}" {{ old('kyc_status', $user->kyc_status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @error('kyc_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-6">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_flagged" value="1" {{ old('is_flagged', $user->is_flagged) ? 'checked' : '' }} />
                        Flagged
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="freeze_wallets" value="1" />
                        Freeze wallets
                    </label>
                </div>

                <div class="md:col-span-2 border-t pt-4">
                    <div class="text-sm font-semibold text-gray-900">Seguridad</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="new_password">Nuevo password (opcional)</label>
                            <input id="new_password" name="new_password" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="new_pin">Nuevo PIN (opcional)</label>
                            <input id="new_pin" name="new_pin" type="password" maxlength="4" pattern="\d{4}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                            @error('new_pin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="px-4 py-2 rounded border border-gray-200 text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

