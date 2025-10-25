<x-admin-layout 
title="Permisos"
:breadcrumbs="[
    [
        'name'=>'Permisos',
        'href'=>route('admin.permisos.index'),
    ]
]">

@if(session('swal'))
    <script>
        Swal.fire({
            icon: '{{ session('swal.icon') }}',
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
        });
    </script>
@endif

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800 mb-2">{{ __('Gestión de Permisos') }}</h1>
                <p class="text-gray-600">
                    {{ __('Desde aquí puedes registrar solicitudes de ausentismo o licencia y consultar su estado.') }}
                </p>
            </div>
            <x-wire-button blue href="{{ route('admin.permisos.create') }}">
                <i class="fa-solid fa-plus"></i> Registrar Permiso
            </x-wire-button>
        </div>
    </div>

    {{-- Tabs de navegación --}}
    <div class="mb-4">
        <ul class="flex flex-wrap border-b border-gray-200">
            <li class="mr-2">
                <a href="{{ route('admin.permisos.index', ['tab' => 'pendientes']) }}" 
                   class="inline-block px-6 py-3 rounded-t-lg {{ request('tab', 'pendientes') == 'pendientes' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-clock mr-2"></i>Pendientes
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.permisos.index', ['tab' => 'aprobados']) }}" 
                   class="inline-block px-6 py-3 rounded-t-lg {{ request('tab') == 'aprobados' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-check-circle mr-2"></i>Aprobados
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.permisos.index', ['tab' => 'rechazados']) }}" 
                   class="inline-block px-6 py-3 rounded-t-lg {{ request('tab') == 'rechazados' ? 'bg-white text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-times-circle mr-2"></i>Rechazados
                </a>
            </li>
        </ul>
    </div>

    {{-- Contenido del tab activo --}}
    <x-wire-card>
        @php
            $tab = request('tab', 'pendientes');
            $permisos = match($tab) {
                'aprobados' => $aprobados,
                'rechazados' => $rechazados,
                default => $pendientes,
            };
            
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
                        <th class="px-4 py-3">{{ __('Fecha Solicitud') }}</th>
                        <th class="px-4 py-3">{{ __('Tipo') }}</th>
                        <th class="px-4 py-3">{{ __('Tipo Ausentismo') }}</th>
                        <th class="px-4 py-3">{{ __('Desde') }}</th>
                        <th class="px-4 py-3">{{ __('Hasta') }}</th>
                        <th class="px-4 py-3">{{ __('Días Hábiles') }}</th>
                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($permisos as $indice => $permiso)
                        <tr>
                            <td class="px-4 py-3 text-gray-500">{{ $indice + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $permiso->created_at?->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ ucfirst($permiso->tipo_permiso) }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $permiso->tipo_de_ausentismo ? str_replace('_', ' ', ucfirst($permiso->tipo_de_ausentismo)) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($permiso->fecha_final)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3 text-gray-700 font-semibold">{{ (int) $permiso->dias_habiles }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badgeClasses = $estadoColors[$permiso->estado] ?? 'bg-gray-200 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses }}">
                                    {{ ucfirst($permiso->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                {{ __('No hay permisos ' . $tab . '.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-wire-card>

</x-admin-layout>