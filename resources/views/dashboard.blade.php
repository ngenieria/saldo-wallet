<x-layouts.mobile>
    <!-- SEO Meta Tags -->
    @section('meta')
        <meta name="description" content="Saldo Wallet - La billetera digital global. Envía, recibe y cambia dinero al instante. Pagos QR, transferencias internacionales y más.">
        <meta name="keywords" content="billetera digital, pagos qr, transferencias internacionales, colombia, nequi, daviplata, saldo, wallet, fintech">
        <meta property="og:title" content="Saldo Wallet - Tu dinero sin fronteras">
        <meta property="og:description" content="Gestiona tus finanzas, paga con QR y envía dinero a amigos. Rápido, seguro y fácil.">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary_large_image">
        
        <!-- PWA Manifest Link -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#2563eb">
    @endsection

    <div class="min-h-screen bg-gray-50 pb-24 relative overflow-hidden" x-data="{ activeTab: 'home', scanOpen: false, scanStatus: 'scanning', profileOpen: false, addContactOpen: false }">
        
        <!-- Blue Header Background (Full Cover) -->
        <div class="bg-blue-600 h-[320px] absolute top-0 left-0 w-full rounded-b-[3rem] z-0 shadow-lg"></div>

        <!-- Top Status Bar Area -->
        <div class="relative z-10 px-6 pt-10 pb-4 text-white">
            <div class="flex justify-between items-center mb-6">
                <!-- Brand Logo -->
                <h1 class="text-2xl font-extrabold tracking-tight">Saldo.</h1>
                
                <!-- Notification Bell -->
                <button class="relative p-2 text-white hover:text-blue-100 transition rounded-full hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-2 right-2 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-blue-600"></span>
                </button>
            </div>

            <!-- User Greeting -->
            <div class="flex items-center space-x-4 mb-6">
                <div class="h-12 w-12 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white font-bold text-xl border-2 border-white/30 shadow-inner">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm text-blue-100 font-medium opacity-90">Welcome Back,</p>
                    <h2 class="text-xl font-bold leading-tight">{{ Auth::user()->name }}</h2>
                </div>
            </div>
        </div>

        <div class="relative z-10 px-6 space-y-8 -mt-4">
            
            <!-- Main Balance Card -->
            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-blue-900/10 text-gray-800 backdrop-blur-xl border border-white/50">
                <div class="mb-8 text-center">
                    <p class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Total Balance</p>
                    <div class="flex items-baseline justify-center gap-1">
                        <span class="text-2xl text-gray-400 font-bold">$</span>
                        <h1 class="text-5xl font-extrabold text-gray-900 tracking-tighter">
                            {{ number_format($wallets->where('currency', 'USD')->first()->balance ?? 0, 2) }}
                        </h1>
                        <span class="text-xl font-bold text-gray-400 ml-1">USD</span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-4">
                    <!-- Action Buttons -->
                    <button @click="activeTab = 'transfer'; $nextTick(() => $el.scrollIntoView({behavior: 'smooth', block: 'center'}))" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Send</span>
                    </button>
                    
                    <button class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Top Up</span>
                    </button>

                    <button @click="activeTab = 'exchange'; $nextTick(() => $el.scrollIntoView({behavior: 'smooth', block: 'center'}))" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">Swap</span>
                    </button>

                    <button @click="scanOpen = true; scanStatus = 'scanning'; setTimeout(() => { scanStatus = 'success' }, 3000)" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-600">QRIS</span>
                    </button>
                </div>
            </div>

            <!-- Quick Send Section -->
            <div>
                <h3 class="text-gray-900 font-bold text-lg mb-4 flex items-center gap-2">
                    Quick Send
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase">Fast</span>
                </h3>
                <div class="flex gap-5 overflow-x-auto pb-4 scrollbar-hide px-1">
                    <button @click="addContactOpen = true" class="flex flex-col items-center space-y-2 min-w-[64px] group">
                        <div class="w-16 h-16 rounded-full bg-white border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:border-blue-400 hover:text-blue-500 transition duration-300 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 group-hover:text-blue-600">Add New</span>
                    </button>
                    
                    @foreach ($contacts as $contact)
                    <button @click="document.getElementById('recipient').value = '{{ $contact->contactUser->email }}'; activeTab = 'transfer'; $nextTick(() => document.getElementById('transfer-form').scrollIntoView({behavior: 'smooth', block: 'center'}))" class="flex flex-col items-center space-y-2 min-w-[64px] group">
                        <div class="relative w-16 h-16 transition transform group-active:scale-95">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($contact->nickname) }}&background=random&color=fff&size=128" class="w-full h-full rounded-full border-2 border-white shadow-md group-hover:shadow-lg transition duration-300 object-cover" alt="User">
                        </div>
                        <span class="text-xs font-semibold text-gray-600 group-hover:text-gray-900 truncate w-16 text-center">{{ $contact->nickname }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Add Contact Modal -->
            <div x-show="addContactOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-6" style="display: none;">
                <div @click.away="addContactOpen = false" class="bg-white rounded-[2rem] p-6 w-full max-w-sm shadow-2xl">
                    <h3 class="text-xl font-bold mb-4">Add Contact</h3>
                    <form action="{{ route('contacts.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" required placeholder="friend@example.com" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="addContactOpen = false" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl">Cancel</button>
                            <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transfer Form (Hidden by default) -->
            <div id="transfer-form" x-show="activeTab === 'transfer'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[2rem] p-6 shadow-xl border border-gray-100" style="display: none;">
                <h3 class="text-gray-900 font-bold text-lg mb-6 flex justify-between items-center">
                    Send Money 
                    <button @click="activeTab = 'home'" class="text-gray-400 hover:text-red-500 transition p-2 bg-gray-50 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </h3>
                <form action="{{ route('transfer') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Recipient</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                <input id="recipient" type="text" name="recipient" placeholder="Email or Phone" class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition outline-none font-medium">
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Amount</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-800 font-bold">$</span>
                                    <input type="number" name="amount" step="0.01" placeholder="0.00" class="w-full pl-8 pr-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition outline-none font-bold text-lg text-gray-900">
                                </div>
                            </div>
                            <div class="w-1/3">
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Currency</label>
                                <div class="relative">
                                    <select name="currency" class="w-full pl-3 pr-8 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition outline-none appearance-none font-bold text-gray-700">
                                        @foreach ($wallets as $wallet)
                                            <option value="{{ $wallet->currency }}">{{ $wallet->currency }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Security PIN</label>
                            <input type="password" name="pin" maxlength="4" placeholder="••••" class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border border-gray-200 focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition outline-none text-center tracking-[0.5em] text-xl font-bold">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition transform active:scale-[0.98]">
                            Send Money Now
                        </button>
                    </div>
                </form>
            </div>

            <!-- Transaction History -->
            <div class="pb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-gray-900 font-bold text-lg">Recent Activity</h3>
                    <a href="#" class="text-blue-600 text-sm font-bold hover:underline bg-blue-50 px-3 py-1 rounded-full">See All</a>
                </div>
                
                <div class="space-y-4">
                    @forelse ($transactions as $transaction)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-50 flex items-center justify-between hover:bg-gray-50 transition duration-200 cursor-pointer group">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 rounded-2xl {{ $transaction->type == 'transfer' ? 'bg-purple-100 text-purple-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center group-hover:scale-110 transition">
                                @if($transaction->type == 'transfer')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 capitalize text-sm mb-0.5">{{ $transaction->type }}</h4>
                                <p class="text-xs text-gray-500 font-medium">{{ $transaction->created_at->format('M d, h:i A') }}</p>
                            </div>
                        </div>
                        
                        @php
                            $isSender = $wallets->contains('id', $transaction->sender_wallet_id);
                        @endphp
                        <div class="text-right">
                            <p class="font-bold {{ $isSender ? 'text-gray-900' : 'text-green-600' }} text-base">
                                {{ $isSender ? '-' : '+' }} ${{ number_format($transaction->amount, 2) }}
                            </p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{{ $transaction->currency }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 bg-white rounded-[2rem] border-2 border-dashed border-gray-200">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No transactions yet.</p>
                        <button class="mt-4 text-blue-600 text-xs font-bold uppercase tracking-wide hover:underline">Start Transacting</button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scan Modal (Overlay) -->
    <div x-show="scanOpen" x-cloak class="fixed inset-0 z-[60] bg-black/95 backdrop-blur-sm flex flex-col items-center justify-center p-6" style="display: none;">
        <div class="w-full max-w-sm relative">
            <button @click="scanOpen = false; scanStatus = 'scanning'" class="absolute -top-16 right-0 text-white p-2 hover:bg-white/10 rounded-full transition">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="bg-white rounded-[2.5rem] p-8 text-center shadow-2xl overflow-hidden relative">
                <!-- Status Message -->
                <div x-show="scanStatus === 'scanning'">
                    <h3 class="text-2xl font-bold mb-2 text-gray-900">Scan QR Code</h3>
                    <p class="text-gray-500 text-sm mb-8 font-medium">Align code within the frame to pay</p>
                    
                    <div class="relative w-64 h-64 mx-auto bg-gray-900 rounded-3xl overflow-hidden border-4 border-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <!-- Camera Feed Simulation -->
                        <div class="absolute inset-0 opacity-50 bg-[url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60')] bg-cover bg-center"></div>
                        
                        <div class="relative z-10 text-center">
                            <svg class="w-12 h-12 text-white/50 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <p class="text-[10px] text-white/70 mt-2 font-bold uppercase tracking-widest">Scanning...</p>
                        </div>
                        
                        <!-- Scan Line Animation -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-blue-400 shadow-[0_0_20px_#60a5fa] animate-[scan_2s_infinite]"></div>
                        
                        <!-- Corner Markers -->
                        <div class="absolute top-4 left-4 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-lg"></div>
                        <div class="absolute top-4 right-4 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-lg"></div>
                        <div class="absolute bottom-4 left-4 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-lg"></div>
                        <div class="absolute bottom-4 right-4 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-lg"></div>
                    </div>
                </div>

                <!-- Success State -->
                <div x-show="scanStatus === 'success'" style="display: none;">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2 text-gray-900">Code Detected!</h3>
                    <p class="text-gray-500 text-sm mb-6">Redirecting to payment...</p>
                    <button @click="scanOpen = false; activeTab = 'transfer'; document.getElementById('recipient').value = 'Store Payment'; $nextTick(() => document.getElementById('transfer-form').scrollIntoView({behavior: 'smooth', block: 'center'}))" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200">
                        Continue to Pay
                    </button>
                </div>

                <div class="mt-8 flex justify-center gap-6" x-show="scanStatus === 'scanning'">
                    <button class="bg-gray-50 p-4 rounded-full text-gray-600 hover:bg-gray-100 transition shadow-sm border border-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </button>
                    <button class="bg-gray-50 p-4 rounded-full text-gray-600 hover:bg-gray-100 transition shadow-sm border border-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Sidebar (Right Side) -->
    <div x-show="profileOpen" class="fixed inset-0 z-50 flex justify-end" style="display: none;">
        <!-- Backdrop -->
        <div @click="profileOpen = false" x-show="profileOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
        
        <!-- Sidebar Content -->
        <div x-show="profileOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative w-[85%] max-w-sm bg-white h-full shadow-2xl overflow-y-auto z-50 rounded-l-[2.5rem]">
            
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <button @click="profileOpen = false" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button class="p-2 -mr-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>

                <!-- User Profile Info -->
                <div class="text-center mb-10">
                    <div class="relative w-24 h-24 mx-auto mb-4">
                        <div class="w-full h-full rounded-full bg-blue-100 flex items-center justify-center text-3xl font-bold text-blue-600 border-4 border-white shadow-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <!-- Verified Badge -->
                        <div class="absolute bottom-1 right-1 bg-blue-500 text-white p-1 rounded-full border-2 border-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center justify-center gap-1">
                        {{ Auth::user()->name }}
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </h2>
                    <p class="text-gray-400 text-sm mt-1">{{ Auth::user()->mobile_phone ?? Auth::user()->email }}</p>
                    
                    <button class="mt-4 px-6 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold hover:bg-blue-100 transition">
                        View Profile
                    </button>
                </div>

                <!-- Menu Items -->
                <nav class="space-y-6">
                    <a href="#" class="flex items-center space-x-4 text-blue-600 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Home</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-4 text-gray-500 hover:text-blue-600 transition font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Transaction History</span>
                    </a>

                    <a href="#" class="flex items-center space-x-4 text-gray-500 hover:text-blue-600 transition font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        <span>Spending Insights</span>
                    </a>

                    <a href="#" class="flex items-center space-x-4 text-gray-500 hover:text-blue-600 transition font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Split Bills</span>
                    </a>

                    <a href="#" class="flex items-center space-x-4 text-gray-500 hover:text-blue-600 transition font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Saved Contact</span>
                    </a>

                    <a href="#" class="flex items-center space-x-4 text-gray-500 hover:text-blue-600 transition font-medium">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Settings</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-4 text-red-500 hover:text-red-600 transition font-bold w-full text-left">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>

                <!-- Promo Banner -->
                <div class="mt-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-sm uppercase tracking-wider opacity-80">Try Now!</span>
                        </div>
                        <h3 class="font-bold text-lg leading-tight mb-2">Smart Auto-Budgeting</h3>
                        <p class="text-xs text-indigo-100 mb-4 opacity-90">Manage your money effortlessly with AI.</p>
                        <button class="w-full bg-white text-indigo-600 py-2 rounded-lg font-bold text-sm shadow-md">Activate</button>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-white opacity-10 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation (Fixed) -->
    <div class="fixed bottom-0 w-full bg-white border-t border-gray-100 px-6 py-2 flex justify-between items-end z-40 pb-safe shadow-[0_-10px_40px_rgba(0,0,0,0.05)] h-[88px]">
        <button @click="activeTab = 'home'" :class="activeTab === 'home' || activeTab === 'transfer' || activeTab === 'exchange' ? 'text-blue-600' : 'text-gray-400'" class="flex flex-col items-center gap-1.5 transition w-16 mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-bold tracking-wide">Home</span>
        </button>
        <button class="text-gray-400 flex flex-col items-center gap-1.5 hover:text-blue-600 transition w-16 mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="text-[10px] font-bold tracking-wide">Stats</span>
        </button>
        
        <!-- Floating Scan Button -->
        <div class="relative -top-8">
            <button @click="scanOpen = true; scanStatus = 'scanning'; setTimeout(() => { scanStatus = 'success' }, 3000)" class="bg-blue-600 text-white p-5 rounded-full shadow-xl shadow-blue-400/50 hover:bg-blue-700 transition transform hover:scale-105 active:scale-95 border-[6px] border-white ring-1 ring-gray-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </button>
        </div>

        <button class="text-gray-400 flex flex-col items-center gap-1.5 hover:text-blue-600 transition w-16 mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <span class="text-[10px] font-bold tracking-wide">Wallet</span>
        </button>
        <button @click="profileOpen = true" class="text-gray-400 flex flex-col items-center gap-1.5 hover:text-blue-600 transition w-16 mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[10px] font-bold tracking-wide">Profile</span>
        </button>
    </div>

    <style>
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</x-layouts.mobile>
