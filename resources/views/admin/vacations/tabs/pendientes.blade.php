<x-wire-card>
    <x-slot name="title">
        <div class="flex items-center gap-2 text-primary-600 font-semibold">
            <i class="fa-solid fa-hourglass-half"></i>
            {{ __('Solicitudes Pendientes') }}
        </div>
    </x-slot>

    @php
        $estadoColors = [
            'pendiente' => 'bg-amber-100 text-amber-700',
            'aprobado' => 'bg-emerald-100 text-emerald-700',
            'rechazado' => 'bg-rose-100 text-rose-700',
        ];
    @endphp

    <section class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-6">
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">{{ __('Empleado') }}</th>
                        <th class="px-4 py-3">{{ __('Fecha Solicitud') }}</th>
                        <th class="px-4 py-3">{{ __('Observaciones') }}</th>
                        <th class="px-4 py-3">{{ __('Desde') }}</th>
                        <th class="px-4 py-3">{{ __('Hasta') }}</th>
                        <th class="px-4 py-3">{{ __('Días Hábiles') }}</th>
                        {{-- Autorizador column removed --}}
                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                        <th class="px-4 py-3">{{ __('Editar') }}</th>
                        <th class="px-4 py-3">{{ __('Cancelar') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($pendientes as $indice => $solicitud)
                        {{-- Vista removida temporalmente: pendientes --}}
                            <td class="px-4 py-3 text-gray-500">
