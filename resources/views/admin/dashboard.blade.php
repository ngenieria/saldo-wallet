<x-layouts.admin>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Transactions</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900">{{ $stats['total_transactions'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pending KYC</h3>
            <p class="text-3xl font-bold mt-2 text-yellow-600">{{ $stats['pending_kyc'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($recent_users ?? [] as $user)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->kyc_status === 'approved' ? 'bg-green-100 text-green-800' : ($user->kyc_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($user->kyc_status) }}
                    </span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No users yet.</li>
                @endforelse
            </ul>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Transactions</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($recent_transactions ?? [] as $transaction)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $transaction->type }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-sm font-medium text-gray-900">
                        {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                    </span>
                </li>
                @empty
                <li class="px-6 py-4 text-gray-500 text-sm">No transactions yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-layouts.admin>
