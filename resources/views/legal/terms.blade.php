<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Términos y Condiciones · Saldo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="antialiased font-sans bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-10">
        <a href="{{ url('/' . ($locale ?? 'es-CO')) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-gray-900">
            <span class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center">←</span>
            Volver
        </a>

        <div class="mt-6 bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
            <h1 class="text-2xl font-black text-gray-900">Términos y Condiciones</h1>
            <p class="mt-2 text-sm text-gray-500">Última actualización: {{ date('Y-m-d') }}</p>

            <div class="mt-8 space-y-6 text-sm text-gray-700 leading-6">
                <p>
                    Estos Términos y Condiciones regulan el acceso y uso de la plataforma “Saldo” (la “Plataforma”).
                    Al registrarte o usar la Plataforma, aceptas estos términos.
                </p>

                <h2 class="text-lg font-bold text-gray-900">1. Identificación del responsable</h2>
                <p>
                    El responsable de la Plataforma será identificado en el sitio con la razón social, NIT y domicilio.
                    Si estás en Colombia, aplican las normas locales de protección al consumidor, comercio electrónico y protección de datos.
                </p>

                <h2 class="text-lg font-bold text-gray-900">2. Registro y cuenta</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Debes proporcionar información veraz, completa y actualizada.</li>
                    <li>Eres responsable de mantener la confidencialidad de tus credenciales.</li>
                    <li>Podemos requerir verificaciones (OTP, KYC) por seguridad y cumplimiento.</li>
                </ul>

                <h2 class="text-lg font-bold text-gray-900">3. Seguridad</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Implementamos controles como OTP por SMS y correo, y verificación de dispositivos.</li>
                    <li>Nunca debes compartir tu OTP, contraseña o PIN. Saldo no solicita códigos por redes sociales.</li>
                    <li>Podemos bloquear accesos o transacciones ante señales de riesgo o fraude.</li>
                </ul>

                <h2 class="text-lg font-bold text-gray-900">4. Uso permitido</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Está prohibido usar la Plataforma para actividades ilícitas o fraude.</li>
                    <li>Está prohibido vulnerar la seguridad, extraer datos o afectar la disponibilidad.</li>
                </ul>

                <h2 class="text-lg font-bold text-gray-900">5. Comisiones y límites</h2>
                <p>
                    Podrán existir comisiones, límites y reglas de operación que se informarán en la Plataforma.
                    Los límites pueden ajustarse por verificación, riesgo, cumplimiento o regulación.
                </p>

                <h2 class="text-lg font-bold text-gray-900">6. Propiedad intelectual</h2>
                <p>
                    La Plataforma, su diseño y contenidos son propiedad del responsable o de sus licenciantes.
                    Se prohíbe el uso no autorizado.
                </p>

                <h2 class="text-lg font-bold text-gray-900">7. Suspensión y terminación</h2>
                <p>
                    Podemos suspender o terminar el acceso ante incumplimiento, fraude o riesgos de seguridad.
                    También puedes solicitar el cierre de tu cuenta según los canales de soporte.
                </p>

                <h2 class="text-lg font-bold text-gray-900">8. Limitación de responsabilidad</h2>
                <p>
                    La Plataforma se ofrece “tal cual”. No garantizamos disponibilidad ininterrumpida.
                    No respondemos por daños indirectos, salvo lo exigido por la ley aplicable.
                </p>

                <h2 class="text-lg font-bold text-gray-900">9. Ley aplicable y jurisdicción</h2>
                <p>
                    Para usuarios en Colombia, estos términos se interpretan de acuerdo con las leyes colombianas.
                    Cualquier controversia se tramitará ante la autoridad o jueces competentes según el caso.
                </p>

                <h2 class="text-lg font-bold text-gray-900">10. Contacto</h2>
                <p>
                    Para soporte: <a class="text-emerald-700 font-semibold hover:underline" href="mailto:support@saldo.com.co">support@saldo.com.co</a>
                </p>
            </div>
        </div>
    </div>

    <x-chatbot />
</body>
</html>

