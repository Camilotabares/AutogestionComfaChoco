<x-admin-simple-layout title="{{ __('Editar Solicitud de Vacaciones') }}">
    <div class="max-w-4xl mx-auto space-y-6 px-4">
        <x-wire-card>
            <x-slot name="title">
                <div class="flex items-center gap-2 text-primary-600 font-semibold">
                    <i class="fa-solid fa-pen-to-square"></i>
                    {{ __('Editar Solicitud') }}
                </div>
            </x-slot>

            <form method="POST" action="{{ route('admin.vacaciones.update', $solicitud) }}" class="grid gap-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <x-wire-input label="{{ __('Empleado') }}" :value="$solicitud->nombre" readonly />
                    <x-wire-input label="{{ __('Cédula') }}" :value="$solicitud->cedula" readonly />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-wire-input label="{{ __('Fecha Inicio') }}" type="date" name="fecha_inicio" :value="$solicitud->fecha_inicio?->format('Y-m-d')" required />
                    <x-wire-input label="{{ __('Fecha Fin') }}" type="date" name="fecha_fin" :value="$solicitud->fecha_fin?->format('Y-m-d')" required />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-wire-input label="{{ __('Días Hábiles') }}" type="number" min="0" name="dias_habiles" :value="$solicitud->dias_habiles" required />
                        <!-- Autorizador input removed -->
                </div>

                <x-wire-textarea label="{{ __('Observaciones') }}" name="observaciones">{{ old('observaciones', $solicitud->observaciones) }}</x-wire-textarea>

                @if ($errors->any())
                    <div class="p-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <x-wire-button primary type="submit">
                        {{ __('Guardar cambios') }}
                    </x-wire-button>
                    <x-wire-button href="{{ route('admin.vacaciones.index', ['tab' => 'consultas']) }}" flat>
                        {{ __('Cancelar') }}
                    </x-wire-button>
                </div>
            </form>
        </x-wire-card>
    </div>
</x-admin-simple-layout>
