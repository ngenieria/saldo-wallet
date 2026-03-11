<x-layouts.guest>
    <div class="w-full">
        <div class="mb-5">
            <h1 class="text-2xl font-bold text-gray-900">Verification Code Sent!</h1>
            @if ($method === 'totp')
                <p class="mt-2 text-sm text-gray-500">Ingresa el código de tu autenticador para continuar.</p>
            @else
                <p class="mt-2 text-sm text-gray-500">
                    Enviamos un código a
                    <span class="font-semibold text-gray-900">{{ $maskedPhone ?? 'tu número' }}</span>
                    @if (!empty($maskedEmail))
                        y a <span class="font-semibold text-gray-900">{{ $maskedEmail }}</span>
                    @endif
                    .
                </p>
            @endif
        </div>

        @if ($method === 'totp')
            <form method="POST" action="{{ route('verification.verify') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="text-xs font-bold text-gray-500 uppercase ml-1 mb-1 block">Código</label>
                    <input id="code" class="w-full px-4 py-3.5 rounded-2xl bg-gray-50 border {{ $errors->has('code') ? 'border-red-300' : 'border-gray-200' }} text-center tracking-[0.5em] outline-none focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition" type="text" name="code" inputmode="numeric" maxlength="6" pattern="\d{6}" required autofocus />
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full py-3.5 rounded-2xl bg-blue-600 text-white font-semibold hover:bg-blue-700 active:bg-blue-800 transition">
                    Confirm
                </button>
            </form>
        @else
            <div
                x-data="{
                    digits: ['','','',''],
                    focused: 0,
                    get code(){ return this.digits.join(''); },
                    input(n){
                        if (this.focused >= 4) return;
                        this.digits[this.focused] = String(n);
                        this.focused = Math.min(4, this.focused + 1);
                    },
                    backspace(){
                        if (this.focused === 0 && this.digits[0] === '') return;
                        if (this.focused === 4 || this.digits[this.focused] === '') {
                            this.focused = Math.max(0, this.focused - 1);
                        }
                        this.digits[this.focused] = '';
                    },
                    clear(){
                        this.digits = ['','','',''];
                        this.focused = 0;
                    }
                }"
                class="space-y-4"
            >
                <form method="POST" action="{{ route('verification.verify') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="code" :value="code">

                    <div class="flex justify-center gap-3">
                        <template x-for="(d, i) in digits" :key="i">
                            <div class="w-14 h-14 rounded-2xl border bg-gray-50 flex items-center justify-center text-xl font-bold"
                                 :class="{
                                    'border-blue-500 ring-4 ring-blue-100 bg-white': focused === i,
                                    'border-gray-200': focused !== i
                                 }"
                            >
                                <span x-text="d"></span>
                            </div>
                        </template>
                    </div>

                    @error('code')
                        <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full py-3.5 rounded-2xl font-semibold transition"
                        :class="code.length === 4 ? 'bg-blue-600 text-white hover:bg-blue-700 active:bg-blue-800' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                        :disabled="code.length !== 4"
                    >
                        Confirm
                    </button>
                </form>

                <div class="grid grid-cols-3 gap-3 select-none">
                    <template x-for="n in [1,2,3,4,5,6,7,8,9]" :key="n">
                        <button type="button" @click="input(n)" class="h-14 rounded-2xl bg-gray-50 border border-gray-200 text-lg font-semibold active:bg-gray-100">
                            <span x-text="n"></span>
                        </button>
                    </template>
                    <button type="button" @click="clear()" class="h-14 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-semibold text-gray-600 active:bg-gray-100">
                        Clear
                    </button>
                    <button type="button" @click="input(0)" class="h-14 rounded-2xl bg-gray-50 border border-gray-200 text-lg font-semibold active:bg-gray-100">
                        0
                    </button>
                    <button type="button" @click="backspace()" class="h-14 rounded-2xl bg-gray-50 border border-gray-200 text-sm font-semibold text-gray-600 active:bg-gray-100">
                        Delete
                    </button>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                            Resend Code
                        </button>
                    </form>
                    <a class="text-sm font-semibold text-gray-500 hover:text-gray-700" href="{{ route('login') }}">
                        Change login
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.guest>
