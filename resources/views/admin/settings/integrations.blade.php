<x-layouts.admin>
    <div class="space-y-8">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Mantenimiento</h2>
            <div class="text-sm text-gray-600">Si hiciste cambios y no se reflejan, limpia la cache de vistas/config.</div>
            <form method="POST" action="{{ route('admin.settings.tools.clearCache') }}" class="mt-4">
                @csrf
                <button class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">Limpiar cache</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">SMTP</h2>

            <form method="POST" action="{{ route('admin.settings.integrations.save') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_host">Host</label>
                    <input id="mail_host" name="mail_host" value="{{ old('mail_host', $mail_host) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="mail.saldo.com.co" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_port">Port</label>
                    <input id="mail_port" name="mail_port" value="{{ old('mail_port', $mail_port) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="465" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_username">Username</label>
                    <input id="mail_username" name="mail_username" value="{{ old('mail_username', $mail_username) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="verify@saldo.com.co" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_password">Password (no se muestra)</label>
                    <input id="mail_password" name="mail_password" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="••••••••" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_encryption">Encryption</label>
                    <input id="mail_encryption" name="mail_encryption" value="{{ old('mail_encryption', $mail_encryption) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="ssl / tls" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="mail_from_address">From Address</label>
                    <input id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $mail_from_address) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="verify@saldo.com.co" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700" for="mail_from_name">From Name</label>
                    <input id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $mail_from_name) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="Saldo" />
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.settings.integrations.testEmail') }}" class="mt-6 flex flex-col md:flex-row gap-3">
                @csrf
                <input name="to" class="flex-1 rounded border-gray-300 p-2 border" placeholder="correo@destino.com" />
                <button class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">Enviar prueba</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Twilio SMS</h2>

            <form method="POST" action="{{ route('admin.settings.integrations.save') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="sms_driver">SMS Driver</label>
                    <input id="sms_driver" name="sms_driver" value="{{ old('sms_driver', $sms_driver) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="twilio" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="twilio_account_sid">Account SID</label>
                    <input id="twilio_account_sid" name="twilio_account_sid" value="{{ old('twilio_account_sid', $twilio_account_sid) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="twilio_auth_token">Auth Token (no se muestra)</label>
                    <input id="twilio_auth_token" name="twilio_auth_token" type="password" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="••••••••" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="twilio_from">From</label>
                    <input id="twilio_from" name="twilio_from" value="{{ old('twilio_from', $twilio_from) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="+1XXXXXXXXXX" />
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3">
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Guardar</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.settings.integrations.testSms') }}" class="mt-6 flex flex-col md:flex-row gap-3">
                @csrf
                <input name="to" class="flex-1 rounded border-gray-300 p-2 border" placeholder="+573001112233" />
                <button class="px-4 py-2 rounded bg-gray-800 text-white hover:bg-gray-900">Enviar prueba</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
