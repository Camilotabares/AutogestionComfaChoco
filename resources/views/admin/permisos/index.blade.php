<x-admin-layout 
title="Permisos"
:breadcrumbs="[
    [
        'name'=>'Permisos',
        'href'=>route('admin.permisos.index'),
    ],
    [
        'name'=>'Registrar Ausentismo',   
    ]
]"> 
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('Bienvenido ') }} {{ auth()->user()->name }}</h1>
                <p class="text-gray-600">
                    {{ __('Desde este panel puedes registrar y consultar solicitudes de ausentismo o licencia. Una vez enviadas, serán revisadas por el área de RRHH para su aprobación.') }}
                </p>
            </div>
            <x-wire-button blue href="{{ route('admin.permisos.create') }}">
                <i class="fa-solid fa-plus"></i> Registrar Ausentismo
            </x-wire-button>
        </div>
    </div>

    @if(auth()->user()->hasRole('empleado'))
        {{-- Mostrar mis solicitudes para empleados --}}
        <x-wire-card>
            <x-slot name="title">
                <div class="flex items-center gap-2 text-primary-600 font-semibold">
                    <i class="fa-solid fa-list"></i>
                    {{ __('Mis Solicitudes') }}
                </div>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">{{ __('Tipo') }}</th>
                            <th class="px-4 py-3">{{ __('Tipo Ausentismo') }}</th>
                            <th class="px-4 py-3">{{ __('Desde') }}</th>
                            <th class="px-4 py-3">{{ __('Hasta') }}</th>
                            <th class="px-4 py-3">{{ __('Estado') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($pendientes as $indice => $permiso)
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-amber-100 text-amber-700',
                                    'aprobado' => 'bg-emerald-100 text-emerald-700',
                                    'rechazado' => 'bg-rose-100 text-rose-700',
                                ];
                                $badgeClasses = $estadoColors[$permiso->estado] ?? 'bg-gray-200 text-gray-700';
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-gray-500">{{ $indice + 1 }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ ucfirst($permiso->tipo_permiso) }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $permiso->tipo_de_ausentismo ? str_replace('_', ' ', ucfirst($permiso->tipo_de_ausentismo)) : '—' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_final)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                        {{ ucfirst($permiso->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    {{ __('No tienes solicitudes registradas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-wire-card>
    @endif

</x-admin-layout>