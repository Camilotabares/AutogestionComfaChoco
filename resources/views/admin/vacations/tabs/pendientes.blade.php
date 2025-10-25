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
                                @can('vacaciones.approve')
                                    @if($solicitud->estado === 'pendiente')
                                        @if(auth()->user()->hasRole('supervisor') && $solicitud->dias_habiles > 3)
                                            <span class="text-xs text-gray-500">
                                                Requiere RRHH
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('admin.vacaciones.approve', $solicitud->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-green-600 hover:text-green-800">
                                                    <i class="fa-solid fa-check"></i>
                                                    {{ __('Aprobar') }}
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endcan
                            </td>
                            <td class="px-4 py-3">
                                @can('vacaciones.reject')
                                    @if($solicitud->estado === 'pendiente')
                                        <form method="POST" action="{{ route('admin.vacaciones.reject', $solicitud->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800">
                                                <i class="fa-solid fa-xmark"></i>
                                                {{ __('Rechazar') }}
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-gray-500">
                                {{ __('No hay solicitudes pendientes.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </section>
</x-wire-card>