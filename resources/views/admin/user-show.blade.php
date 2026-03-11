<x-layouts.admin>
    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-lg font-semibold text-gray-900">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $user->email }} · {{ $user->mobile_phone }}</div>
                    <div class="mt-2 flex gap-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->kyc_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            KYC: {{ ucfirst($user->kyc_status) }}
                        </span>
                        @if ($user->is_flagged)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Flagged</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900">Editar</a>
                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                        @csrf
                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-semibold text-gray-900 mb-3">Wallets</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Currency</th>
                            <th class="py-2">Balance</th>
                            <th class="py-2">Frozen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($user->wallets as $w)
                            <tr>
                                <td class="py-2 font-mono">{{ $w->currency }}</td>
                                <td class="py-2">{{ $w->balance }}</td>
                                <td class="py-2">{{ $w->is_frozen ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                        @if ($user->wallets->isEmpty())
                            <tr><td class="py-2 text-gray-500" colspan="3">Sin wallets.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-semibold text-gray-900 mb-3">Devices</div>
                <div class="space-y-3">
                    @forelse ($devices as $d)
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-900">{{ $d->device_name ?? 'Unknown' }} · {{ $d->os ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $d->ip_address }} · {{ $d->last_active_at?->diffForHumans() }}</div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $d->is_trusted ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $d->is_trusted ? 'Trusted' : 'Untrusted' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Sin dispositivos.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-semibold text-gray-900 mb-3">Login Logs</div>
                <div class="space-y-3">
                    @forelse ($logins as $l)
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-900">{{ $l->ip_address }}</div>
                                <div class="text-xs text-gray-500">{{ $l->created_at?->diffForHumans() }}</div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $l->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($l->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Sin logins.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

