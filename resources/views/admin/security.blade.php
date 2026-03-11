<x-layouts.admin>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Fraud Alerts</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($fraud as $item)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item->type }}</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->severity === 'high' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($item->severity) }}</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No fraud alerts.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">AML Flags</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($aml as $item)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item->type }}</p>
                        <p class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($item->status) }}</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No AML flags.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Suspicious Logins</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($suspiciousLogins as $log)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $log->ip_address }}</p>
                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No suspicious logins.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">New Device Logins</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($newDevices as $d)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $d->device_name ?? 'Unknown' }} ({{ $d->os ?? 'N/A' }})</p>
                        <p class="text-xs text-gray-500">{{ $d->last_active_at?->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Untrusted</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No new device logins.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Admin Login Failures</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($adminLoginFails as $log)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $log->ip_address }}</p>
                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No admin login failures.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden md:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Large Transactions</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($largeTx as $t)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ number_format($t->amount, 2) }} {{ $t->currency }}</p>
                        <p class="text-xs text-gray-500">{{ $t->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ strtoupper($t->type) }}</span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No large transactions.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.admin>
