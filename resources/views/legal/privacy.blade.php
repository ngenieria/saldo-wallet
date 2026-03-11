<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Tratamiento de Datos · Saldo</title>
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
            <h1 class="text-2xl font-black text-gray-900">Política de Tratamiento de Datos Personales</h1>
            <p class="mt-2 text-sm text-gray-500">Última actualización: {{ date('Y-m-d') }}</p>

            <div class="mt-8 space-y-6 text-sm text-gray-700 leading-6">
                <p>
                    Esta política describe cómo tratamos datos personales en Saldo (“la Plataforma”). Para Colombia, se referencia la Ley 1581 de 2012
                    y el Decreto 1377 de 2013 (y normas relacionadas).
                </p>

                <h2 class="text-lg font-bold text-gray-900">1. Responsable del tratamiento</h2>
                <p>
                    El responsable se identificará con razón social, NIT, domicilio y canales de atención en el sitio.
                </p>

                <h2 class="text-lg font-bold text-gray-900">2. Datos que podemos recolectar</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Datos de identificación y contacto: nombre, email, teléfono.</li>
                    <li>Datos de seguridad: IP, dispositivo, logs de acceso, OTP y auditoría.</li>
                    <li>Datos de verificación: información necesaria para KYC cuando aplique.</li>
                </ul>

                <h2 class="text-lg font-bold text-gray-900">3. Finalidades</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Prestación del servicio y administración de tu cuenta.</li>
                    <li>Seguridad, prevención de fraude, control de accesos y auditoría.</li>
                    <li>Comunicaciones operativas (por ejemplo, envío de OTP y alertas).</li>
                    <li>Cumplimiento de obligaciones legales y requerimientos de autoridades competentes.</li>
                </ul>

                <h2 class="text-lg font-bold text-gray-900">4. Seguridad</h2>
                <p>
                    Aplicamos medidas técnicas y organizacionales razonables para proteger los datos. Ningún sistema es infalible, por lo que no se garantiza seguridad absoluta.
                </p>

                <h2 class="text-lg font-bold text-gray-900">5. Derechos del titular (Colombia)</h2>
                <p>
                    Puedes ejercer derechos de conocer, actualizar, rectificar y suprimir datos, y revocar autorizaciones cuando proceda, conforme a la ley.
                </p>

                <h2 class="text-lg font-bold text-gray-900">6. Cómo ejercer tus derechos</h2>
                <p>
                    Puedes solicitar atención escribiendo a <a class="text-emerald-700 font-semibold hover:underline" href="mailto:support@saldo.com.co">support@saldo.com.co</a>.
                    Podremos pedirte verificación de identidad para proteger tu información.
                </p>

                <h2 class="text-lg font-bold text-gray-900">7. Transferencias y encargados</h2>
                <p>
                    Podremos usar proveedores tecnológicos (por ejemplo, SMS/Email) que actúan como encargados del tratamiento. Solo se comparten datos necesarios para la finalidad.
                </p>

                <h2 class="text-lg font-bold text-gray-900">8. Conservación</h2>
                <p>
                    Conservamos datos por el tiempo necesario para la finalidad y obligaciones legales. Logs de seguridad pueden conservarse para investigación y cumplimiento.
                </p>

                <h2 class="text-lg font-bold text-gray-900">9. Cookies</h2>
                <p>
                    Podemos usar cookies estrictamente necesarias para autenticación y seguridad. Puedes gestionar cookies desde tu navegador.
                </p>

                <h2 class="text-lg font-bold text-gray-900">10. Cambios</h2>
                <p>
                    Podremos actualizar esta política. Publicaremos la versión vigente en la Plataforma.
                </p>
            </div>
        </div>
    </div>

    <x-chatbot />
</body>
</html>

