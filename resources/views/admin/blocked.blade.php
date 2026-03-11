<x-layouts.guest>
    <div class="mb-2 text-lg font-semibold text-gray-900">
        {{ __('Acceso bloqueado') }}
    </div>
    <div class="text-sm text-gray-600">
        {{ __('Tu IP no está permitida para el panel admin.') }}
    </div>
    <div class="mt-4 text-xs text-gray-500">
        IP: {{ $ip }}
    </div>
</x-layouts.guest>

