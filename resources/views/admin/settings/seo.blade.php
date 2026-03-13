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
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Favicon</h2>
            <div class="text-sm text-gray-600">Sube un favicon (.ico o .png). Se publicará en /favicon.ico</div>

            <form method="POST" action="{{ route('admin.settings.seo.favicon') }}" enctype="multipart/form-data" class="mt-4 flex flex-col md:flex-row gap-3 items-start md:items-end">
                @csrf
                <div class="w-full md:w-auto">
                    <label class="block text-sm font-medium text-gray-700" for="favicon">Archivo</label>
                    <input id="favicon" name="favicon" type="file" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('favicon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black">Subir</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">SEO</h2>
            <div class="text-sm text-gray-600">Edita título y descripción por idioma para mejorar el posicionamiento.</div>

            <form method="POST" action="{{ route('admin.settings.seo.save') }}" class="mt-6 space-y-6" x-data="{ tab: '{{ $locales[0] }}' }">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="site_name">Nombre del sitio</label>
                    <input id="site_name" name="site_name" value="{{ old('site_name', $site_name) }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                    @error('site_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($locales as $l)
                        <button type="button" @click="tab='{{ $l }}'" class="px-3 py-2 rounded border text-sm"
                            :class="tab === '{{ $l }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'">
                            {{ $l }}
                        </button>
                    @endforeach
                </div>

                @foreach ($locales as $l)
                    <div x-cloak x-show="tab === '{{ $l }}'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Title ({{ $l }})</label>
                            <input name="title[{{ $l }}]" value="{{ old('title.' . $l, $seo[$l]['title'] ?? '') }}" class="mt-1 w-full rounded border-gray-300 p-2 border" />
                            <div class="text-xs text-gray-500 mt-1">Recomendado: 50–60 caracteres (máx 70)</div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description ({{ $l }})</label>
                            <textarea name="description[{{ $l }}]" rows="3" class="mt-1 w-full rounded border-gray-300 p-2 border">{{ old('description.' . $l, $seo[$l]['description'] ?? '') }}</textarea>
                            <div class="text-xs text-gray-500 mt-1">Recomendado: 120–160 caracteres (máx 170)</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keywords ({{ $l }})</label>
                            <input name="keywords[{{ $l }}]" value="{{ old('keywords.' . $l, $seo[$l]['keywords'] ?? '') }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="wallet, pagos, qr..." />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Robots ({{ $l }})</label>
                            <input name="robots[{{ $l }}]" value="{{ old('robots.' . $l, $seo[$l]['robots'] ?? '') }}" class="mt-1 w-full rounded border-gray-300 p-2 border" placeholder="index,follow" />
                        </div>

                        <div class="md:col-span-2">
                            <div class="bg-gray-50 border border-gray-200 rounded p-4">
                                <div class="text-xs text-gray-500">Vista previa (Google)</div>
                                <div class="mt-1 text-sm font-semibold text-blue-700 truncate">{{ old('title.' . $l, $seo[$l]['title'] ?? '') }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ url('/' . $l) }}</div>
                                <div class="mt-1 text-xs text-gray-700 line-clamp-2">{{ old('description.' . $l, $seo[$l]['description'] ?? '') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex items-center justify-end gap-3">
                    <button class="px-4 py-2 rounded bg-gray-900 text-white hover:bg-black">Guardar SEO</button>
                </div>
            </form>

            <div class="mt-6 text-sm text-gray-600">
                Sitemap: <span class="font-mono">{{ url('/sitemap.xml') }}</span>
            </div>
        </div>
    </div>
</x-layouts.admin>

