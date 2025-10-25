<x-admin-layout 
title="Solicitudes Pendientes"
:breadcrumbs="[
    [
        'name'=>'Solicitudes Pendientes',
        'href'=>route('admin.solicitudes-pendientes.index'),
    ]
]"> 

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('Solicitudes Pendientes de Aprobación') }}</h1>
        <p class="text-gray-600">
            {{ __('Aquí puedes revisar y aprobar o rechazar las solicitudes de vacaciones y permisos de los empleados.') }}
        </p>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session('status'))
        <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg">
            <i class="fa-solid fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- Vacaciones Pendientes --}}
    <x-wire-card class="mb-6">
        <x-slot name="title">
            <div class="flex items-center gap-2 text-primary-600 font-semibold">
                <i class="fa-solid fa-umbrella-beach"></i>
                {{ __('Solicitudes de Vacaciones Pendientes') }}
            </div>
        </x-slot>

        @php
            $estadoColors = [
                'pendiente' => 'bg-amber-100 text-amber-700',
                'aprobado' => 'bg-emerald-100 text-emerald-700',
                'rechazado' => 'bg-rose-100 text-rose-700',
            ];
        @endphp

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
                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                        <th class="px-4 py-3">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($vacacionesPendientes as $indice => $solicitud)
                        <tr>
                            <td class="px-4 py-3 text-gray-500">{{ $indice + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700 whitespace-normal">{{ $solicitud->nombre }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $solicitud->created_at?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-500 whitespace-normal">{{ $solicitud->observaciones ?: '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $solicitud->fecha_inicio?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $solicitud->fecha_fin?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $solicitud->dias_habiles }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClasses = $estadoColors[$solicitud->estado] ?? 'bg-gray-200 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ __($solicitud->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($solicitud->estado === 'pendiente')
                                    <div class="flex gap-2">
                                        @if(auth()->user()->hasRole('supervisor') && $solicitud->dias_habiles > 3)
                                            <span class="text-xs text-gray-500">Requiere RRHH</span>
                                        @else
                                            @can('vacaciones.approve')
                                                <form method="POST" action="{{ route('admin.vacaciones.approve', $solicitud->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Aprobar">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                        
                                        @can('vacaciones.reject')
                                            <form method="POST" action="{{ route('admin.vacaciones.reject', $solicitud->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Rechazar">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                {{ __('No hay solicitudes de vacaciones pendientes.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-wire-card>

    {{-- Permisos Pendientes --}}
    <x-wire-card>
        <x-slot name="title">
            <div class="flex items-center gap-2 text-primary-600 font-semibold">
                <i class="fa-solid fa-file-lines"></i>
                {{ __('Solicitudes de Permisos Pendientes') }}
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">{{ __('Empleado') }}</th>
                        <th class="px-4 py-3">{{ __('Tipo') }}</th>
                        <th class="px-4 py-3">{{ __('Tipo Ausentismo') }}</th>
                        <th class="px-4 py-3">{{ __('Desde') }}</th>
                        <th class="px-4 py-3">{{ __('Hasta') }}</th>
                        <th class="px-4 py-3">{{ __('Días') }}</th>
                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                        <th class="px-4 py-3">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($permisosPendientes as $indice => $permiso)
                        @php
                            $dias = \Carbon\Carbon::parse($permiso->fecha_inicio)
                                ->diffInDays(\Carbon\Carbon::parse($permiso->fecha_final)) + 1;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-gray-500">{{ $indice + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $permiso->empleado->nombre ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ ucfirst($permiso->tipo_permiso) }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $permiso->tipo_de_ausentismo ? str_replace('_', ' ', ucfirst($permiso->tipo_de_ausentismo)) : '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_final)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $dias }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClasses = $estadoColors[$permiso->estado] ?? 'bg-gray-200 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ ucfirst($permiso->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($permiso->estado === 'pendiente')
                                    <div class="flex gap-2">
                                        @if(auth()->user()->hasRole('supervisor') && $dias > 3)
                                            <span class="text-xs text-gray-500">Requiere RRHH</span>
                                        @else
                                            @can('permisos.approve')
                                                <form method="POST" action="{{ route('admin.permisos.approve', $permiso->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Aprobar">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                        
                                        @can('permisos.reject')
                                            <form method="POST" action="{{ route('admin.permisos.reject', $permiso->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Rechazar">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                {{ __('No hay solicitudes de permisos pendientes.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-wire-card>

</x-admin-layout>
