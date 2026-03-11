<div
    x-data="{
        open: false,
        input: '',
        messages: [
            { from: 'bot', text: 'Hola, soy SaldoBot. ¿En qué te puedo ayudar? (registro, login, OTP, seguridad, soporte)' }
        ],
        reply(text){
            const t = (text || '').toLowerCase().trim();
            const contains = (k) => t.includes(k);
            if (t === '') return 'Escríbeme una pregunta y te respondo.';

            if (contains('registro') || contains('crear') || contains('cuenta')) {
                return 'Para crear tu cuenta, entra a “Crear cuenta”, completa tus datos y verifica con el código OTP. Si no llega, usa “Reenviar código”.';
            }
            if (contains('login') || contains('iniciar') || contains('sesion')) {
                return 'El inicio de sesión es en 2 pasos: (1) correo/teléfono + password, (2) código OTP de 4 dígitos enviado por SMS y Email.';
            }
            if (contains('otp') || contains('codigo') || contains('código') || contains('verificacion') || contains('verificación')) {
                return 'El OTP expira en pocos minutos. Revisa spam/correo no deseado y confirma que tu email/teléfono estén correctos. Puedes reenviar el código desde la pantalla.';
            }
            if (contains('seguridad') || contains('fraude') || contains('estafa')) {
                return 'Nunca compartas tu código OTP ni tu password. Saldo no solicita códigos por llamadas o redes sociales. Si ves actividad rara, contacta soporte.';
            }
            if (contains('soporte') || contains('ayuda') || contains('contacto') || contains('contactar')) {
                return 'Puedes escribir a support@saldo.com.co. Si estás en el Wallet, también puedes revisar la sección de ayuda.';
            }
            if (contains('admin') || contains('panel')) {
                return 'El panel admin está en admin.saldo.com.co. Si tu hosting no enruta el subdominio, valida que apunte al mismo public/ del proyecto.';
            }

            return 'Puedo ayudarte con registro, login, OTP, seguridad y soporte. ¿Qué necesitas exactamente?';
        },
        send(){
            const text = this.input.trim();
            if (!text) return;
            this.messages.push({ from: 'user', text });
            this.input = '';
            const answer = this.reply(text);
            setTimeout(() => this.messages.push({ from: 'bot', text: answer }), 250);
        }
    }"
    class="fixed bottom-5 right-5 z-50"
>
    <button
        type="button"
        @click="open = !open"
        class="w-14 h-14 rounded-2xl bg-gray-900 text-white shadow-xl flex items-center justify-center hover:bg-black"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h6m7 7l-3-3H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v14z" />
        </svg>
    </button>

    <div x-cloak x-show="open" class="mt-3 w-[340px] bg-white border border-gray-100 rounded-3xl shadow-2xl overflow-hidden">
        <div class="px-4 py-3 bg-gradient-to-r from-emerald-600 to-sky-600 text-white flex items-center justify-between">
            <div class="font-semibold text-sm">SaldoBot</div>
            <button type="button" @click="open=false" class="w-8 h-8 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-4 space-y-3 max-h-[360px] overflow-y-auto">
            <template x-for="(m, idx) in messages" :key="idx">
                <div class="flex" :class="m.from === 'user' ? 'justify-end' : 'justify-start'">
                    <div
                        class="px-3 py-2 rounded-2xl text-sm max-w-[85%]"
                        :class="m.from === 'user' ? 'bg-gray-900 text-white' : 'bg-gray-50 border border-gray-200 text-gray-800'"
                        x-text="m.text"
                    ></div>
                </div>
            </template>
        </div>

        <div class="p-3 border-t border-gray-100">
            <form @submit.prevent="send()" class="flex gap-2">
                <input
                    x-model="input"
                    class="flex-1 px-3 py-2 rounded-2xl bg-gray-50 border border-gray-200 outline-none focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 text-sm"
                    placeholder="Escribe tu pregunta…"
                />
                <button type="submit" class="px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">
                    Enviar
                </button>
            </form>
        </div>
    </div>
</div>

