<x-layouts.admin>
    <div class="space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Two-Factor Authentication (2FA)</h2>

            @if (session('totp_secret'))
                <div class="bg-blue-50 border border-blue-200 text-blue-900 px-4 py-3 rounded mb-4">
                    <div class="text-sm font-medium">Secret TOTP</div>
                    <div class="mt-2 font-mono break-all">{{ session('totp_secret') }}</div>
                    <div class="mt-2 text-xs text-blue-700">Agrega este secret en Google Authenticator y luego verifica con un código.</div>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-700">Estado</div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $admin->two_factor_enabled ? 'Habilitado' : 'Deshabilitado' }}
                        @if ($admin->two_factor_enabled)
                            ({{ strtoupper($admin->two_factor_type) }})
                        @endif
                    </div>
                </div>
                <div class="flex gap-3">
                    @if (!$admin->two_factor_enabled)
                        <form method="POST" action="{{ route('admin.settings.2fa.totp.start') }}">
                            @csrf
                            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Iniciar TOTP</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.settings.2fa.disable') }}">
                            @csrf
                            <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Deshabilitar 2FA</button>
                        </form>
                    @endif
                </div>
            </div>

            @if (!$admin->two_factor_enabled)
                <form method="POST" action="{{ route('admin.settings.2fa.totp.verify') }}" class="mt-4">
                    @csrf
                    <div class="flex items-end gap-3">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700" for="code">Código (6 dígitos)</label>
                            <input id="code" name="code" class="mt-1 w-full rounded border-gray-300 p-2 border" maxlength="6" pattern="\d{6}" inputmode="numeric" />
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Verificar</button>
                    </div>
                </form>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">IP Allowlist</h2>

            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-700">Estado</div>
                    <div class="text-sm font-medium text-gray-900">{{ $admin->ip_allowlist_enabled ? 'Habilitado' : 'Deshabilitado' }}</div>
                </div>
                <div class="flex gap-3">
                    @if (!$admin->ip_allowlist_enabled)
                        <form method="POST" action="{{ route('admin.settings.ip.enable') }}">
                            @csrf
                            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Habilitar</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.settings.ip.disable') }}">
                            @csrf
                            <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Deshabilitar</button>
                        </form>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.ip.add') }}" class="mt-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700" for="ip_address">IP</label>
                        <input id="ip_address" name="ip_address" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="203.0.113.10" />
                        @error('ip_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700" for="label">Etiqueta</label>
                        <input id="label" name="label" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="Oficina" />
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Agregar</button>
                    </div>
                </div>
            </form>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">IP</th>
                            <th class="py-2">Etiqueta</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($ips as $row)
                            <tr>
                                <td class="py-2 font-mono">{{ $row->ip_address }}</td>
                                <td class="py-2">{{ $row->label }}</td>
                                <td class="py-2 text-right">
                                    <form method="POST" action="{{ route('admin.settings.ip.delete', $row->id) }}">
                                        @csrf
                                        <button class="text-red-600 hover:text-red-800">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-2 text-gray-500" colspan="3">No hay IPs registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cambiar Password</h2>

            <form method="POST" action="{{ route('admin.settings.password') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="current_password">Password actual</label>
                    <input id="current_password" name="current_password" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="password">Nuevo password</label>
                    <input id="password" name="password" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="password_confirmation">Confirmación</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                </div>
                <div class="md:col-span-3">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

