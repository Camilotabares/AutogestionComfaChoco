@php
    use Illuminate\Support\Str;
@endphp

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <x-wire-input
                wire:model.live.debounce.500ms="search"
                placeholder="{{ __('Buscar solicitudes') }}"
                icon="magnifying-glass"
            />
            <x-wire-select wire:model.live="perPage" class="w-36">
                <option value="5">5 {{ __('por página') }}</option>
                <option value="10">10 {{ __('por página') }}</option>
                <option value="25">25 {{ __('por página') }}</option>
            </x-wire-select>
        </div>

        <div class="text-sm text-blue-900/70">
            {{ __('Mostrando') }} {{ $vacations->firstItem() ?? 0 }}–{{ $vacations->lastItem() ?? 0 }} {{ __('de') }} {{ $vacations->total() }}
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm text-blue-900">
            <thead class="bg-gradient-to-b from-blue-200 to-blue-300 text-left text-xs font-semibold uppercase tracking-wide text-blue-900">
                <tr>
                    <th class="px-4 py-3 border border-blue-300">#</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Empleado') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Fecha Solicitud') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Observaciones') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Desde') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Hasta') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Días Hábiles') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Estado') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Ver') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Editar') }}</th>
                    <th class="px-4 py-3 border border-blue-300">{{ __('Cancelar') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($vacations as $index => $vacation)
                    <tr class="odd:bg-blue-50/40 even:bg-white">
                        <td class="px-4 py-3 border border-blue-100 text-blue-700">
                            {{ $vacations->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100 font-medium">
                            {{ $vacation->usuario->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            {{ $vacation->created_at?->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100 text-blue-700/80">
                            {{ Str::limit($vacation->observaciones, 40) ?: '—' }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            {{ $vacation->fecha_inicio?->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            {{ $vacation->fecha_fin?->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100 text-center">
                            {{ (int) $vacation->dias_habiles }}
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ __($vacation->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            <x-wire-button flat icon="eye" class="text-blue-600 hover:text-blue-800" />
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            <x-wire-button flat icon="pencil-square" class="text-blue-600 hover:text-blue-800" />
                        </td>
                        <td class="px-4 py-3 border border-blue-100">
                            <x-wire-button flat icon="x-mark" class="text-red-600 hover:text-red-800" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="px-4 py-6 text-center text-blue-900/70 border border-blue-100">
                            {{ __('No hay solicitudes registradas aún.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $vacations->links() }}
    </div>
</div>
