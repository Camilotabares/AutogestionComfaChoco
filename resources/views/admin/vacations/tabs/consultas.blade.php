<x-wire-card>
    <x-slot name="title">
        <div class="flex items-center gap-2 text-primary-600 font-semibold">
            <i class="fa-solid fa-magnifying-glass-chart"></i>
            {{ __('Consultas de Solicitudes') }}
        </div>
    </x-slot>

    <section class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <form method="GET" action="{{ route('admin.vacaciones.index') }}" class="space-y-4 w-full md:w-auto">
                <input type="hidden" name="tab" value="consultas" />
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div class="col-span-1 md:col-span-2">
                        <x-wire-input
                            label="{{ __('Empleado') }}"
                            :value="$empleado?->nombre ?? $usuario?->name ?? '—'"
                            readonly
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Estado') }}</label>
                        <select name="estado" class="mt-1 block w-full min-w-[140px] rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 text-sm">
                            <option value="">{{ __('Todos') }}</option>
                            <option value="pendiente" {{ (isset($selectedEstado) && $selectedEstado === 'pendiente') ? 'selected' : '' }}>{{ __('Pendiente') }}</option>
                            <option value="aprobado" {{ (isset($selectedEstado) && $selectedEstado === 'aprobado') ? 'selected' : '' }}>{{ __('Aprobado') }}</option>
                            <option value="rechazado" {{ (isset($selectedEstado) && $selectedEstado === 'rechazado') ? 'selected' : '' }}>{{ __('Rechazado') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Año') }}</label>
                        <select name="year" class="mt-1 block w-full min-w-[120px] rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 text-sm">
                            <option value="">{{ __('Todos') }}</option>
                            @if(isset($years) && $years->isNotEmpty())
                                @foreach($years as $yr)
                                    <option value="{{ $yr }}" {{ (isset($selectedYear) && (string)$selectedYear === (string)$yr) ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="mt-3 md:mt-0">
                    <x-wire-button primary type="submit" class="px-6">
                        {{ __('Consultar') }}
                    </x-wire-button>
                </div>
            </form>
        </div>

        @php
            $estadoColors = [
                'pendiente' => 'bg-amber-100 text-amber-700',
                'aprobado' => 'bg-emerald-100 text-emerald-700',
                'rechazado' => 'bg-rose-100 text-rose-700',
            ];
        @endphp

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
                        <th class="px-4 py-3">{{ __('Estado') }}</th>
                        <th class="px-4 py-3">{{ __('Editar') }}</th>
                        <th class="px-4 py-3">{{ __('Cancelar') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($solicitudes as $indice => $solicitud)
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
                                    <a href="{{ route('admin.vacaciones.edit', $solicitud) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-800">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('Editar') }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-400 cursor-not-allowed">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('Editar') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($solicitud->estado === 'pendiente')
                                    <form method="POST" action="{{ route('admin.vacaciones.destroy', $solicitud) }}" onsubmit="return confirm('{{ __('¿Cancelar esta solicitud?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-xmark"></i>
                                            {{ __('Cancelar') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-400 cursor-not-allowed">
                                        <i class="fa-solid fa-xmark"></i>
                                        {{ __('Cancelar') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-6 text-center text-gray-500">
                                {{ __('No se han registrado solicitudes para este empleado.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <p class="text-center text-sm text-gray-500 uppercase tracking-wide">
            {{ __('Seleccione la persona de la cual desea consultar las solicitudes') }}
        </p>
    </section>
</x-wire-card>
