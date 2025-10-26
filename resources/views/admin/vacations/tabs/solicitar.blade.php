@php
    $empleado = $empleado ?? null;
    $usuario = $usuario ?? null;
@endphp
    <x-wire-card>
        <x-slot name="title">
            <div class="flex items-center gap-2 text-primary-600 font-semibold">
                <i class="fa-solid fa-plane-departure"></i>
                {{ __('Solicitar Vacaciones') }}
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.vacaciones.store') }}" class="grid gap-6">
            @csrf

            <section class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <header class="flex items-center gap-2 text-sm font-semibold text-primary-600 uppercase tracking-wide mb-4">
                    <i class="fa-solid fa-circle-info"></i>
                    {{ __('Información Empleado') }}
                </header>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <x-wire-input label="{{ __('Cédula') }}" :value="$empleado?->cedula" name="cedula" readonly />
                    <x-wire-input label="{{ __('Empresa') }}" :value="config('app.company_name', 'ComfaCho')" readonly />
                    <x-wire-input label="{{ __('Nombre') }}" :value="$usuario?->name" name="nombre" readonly />
                    <x-wire-input label="{{ __('Días derecho Anual') }}" :value="config('vacaciones.dias_derecho_anual', 15)" readonly />
                    <x-wire-input label="{{ __('Área') }}" :value="$empleado?->area" readonly />
                    <x-wire-input label="{{ __('Fecha Ingreso') }}" :value="$empleado?->fecha_de_ingreso?->format('Y-m-d')" readonly />
                </div>
            </section>

            <section class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <header class="flex items-center gap-2 text-sm font-semibold text-primary-600 uppercase tracking-wide mb-4">
                    <i class="fa-solid fa-calculator"></i>
                    {{ __('Días disponibles') }}
                </header>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="p-3 bg-white border rounded">
                        <div class="text-xs text-gray-500">{{ __('Días acumulados') }}</div>
                        <div class="text-2xl font-semibold text-blue-700">{{ $accrued_net ?? 0 }}</div>
                    </div>

                    <div class="p-3 bg-white border rounded">
                        <div class="text-xs text-gray-500">{{ __('Días tomados') }}</div>
                        <div class="text-2xl font-semibold text-red-600">{{ $days_taken ?? 0 }}</div>
                    </div>

                    <div class="p-3 bg-white border rounded">
                        <div class="text-xs text-gray-500">{{ __('Días disponibles') }}</div>
                        <div class="text-2xl font-semibold text-emerald-600">{{ $days_available ?? 0 }}</div>
                    </div>
                </div>

                @if(isset($has_one_year) && ! $has_one_year)
                    <div class="mt-4 p-3 text-sm text-yellow-800 bg-yellow-100 border border-yellow-200 rounded">
                        {{ __('Aún no cumple 1 año en la empresa. No puede solicitar vacaciones hasta completar su primer aniversario.') }}
                    </div>
                @elseif(isset($accrued_days) && $accrued_days >= 30 && ($days_taken ?? 0) == 0)
                    <div class="mt-4 p-3 text-sm text-orange-800 bg-orange-100 border border-orange-200 rounded">
                        {{ __('Ha alcanzado el tope máximo de acumulación (30 días). Use parte de sus días antes de solicitar más.') }}
                    </div>
                @else
                    @if(isset($min_start_date))
                        <div class="mt-4 text-sm text-gray-700">
                            {{ __('Puede solicitar desde') }}: <strong>{{ $min_start_date->format('Y-m-d') }}</strong>
                        </div>
                    @endif
                @endif
            </section>

            <section class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <header class="flex items-center gap-2 text-sm font-semibold text-primary-600 uppercase tracking-wide mb-4">
                    <i class="fa-solid fa-file-signature"></i>
                    {{ __('Información Solicitud') }}
                </header>

                <div class="space-y-4">
                    <!-- Fecha Inicio -->
                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Desde') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="fecha_inicio"
                            name="fecha_inicio" 
                            value="{{ old('fecha_inicio') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            @if(isset($min_start_date) && $min_start_date && $min_start_date->gt(now()))
                                min="{{ $min_start_date->format('Y-m-d') }}"
                            @endif
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            required
                        />
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Hasta') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="fecha_fin"
                            name="fecha_fin" 
                            value="{{ old('fecha_fin') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            required
                        />
                    </div>

                    <!-- Días Hábiles -->
                    <div>
                        <label for="dias_habiles" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('No. Días Hábiles') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="dias_habiles"
                            name="dias_habiles" 
                            value="{{ old('dias_habiles') }}"
                            min="1"
                            max="{{ $days_available ?? 0 }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                            required
                        />
                    </div>

                    <div class="text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded p-3">
                        <i class="fa-solid fa-info-circle text-blue-600"></i>
                        <strong>Nota:</strong> Los días hábiles se calculan automáticamente excluyendo únicamente domingos (se trabaja de lunes a sábado). 
                        Tiene disponibles <strong class="text-blue-700">{{ $days_available ?? 0 }}</strong> días.
                    </div>

                    <x-wire-textarea label="{{ __('Observaciones') }}" placeholder="{{ __('Agregar observaciones') }}" name="observaciones">{{ old('observaciones') }}</x-wire-textarea>

                    @if ($errors->any())
                        <div class="p-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    @if(isset($can_request) && $can_request)
                        <x-wire-button class="px-6" primary type="submit">
                            {{ __('Enviar Solicitud') }}
                        </x-wire-button>
                    @else
                        <x-wire-button class="px-6" primary type="button" disabled>
                            {{ __('Enviar Solicitud') }}
                        </x-wire-button>
                        <div class="mt-2 text-sm text-gray-600">
                            {{ __('No puede enviar una solicitud en este momento. Revise los motivos indicados arriba.') }}
                        </div>
                    @endif
                </div>
            </section>
        </form>
    </x-wire-card>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            const diasHabiles = document.getElementById('dias_habiles');
            const availableDays = {{ $days_available ?? 0 }};

            function calculateBusinessDays() {
                const startDate = fechaInicio.value;
                const endDate = fechaFin.value;

                if (startDate && endDate) {
                    const start = new Date(startDate + 'T00:00:00');
                    const end = new Date(endDate + 'T00:00:00');
                    
                    if (end < start) {
                        diasHabiles.value = 0;
                        return;
                    }

                    let businessDays = 0;
                    const current = new Date(start);

                    while (current <= end) {
                        const dayOfWeek = current.getDay();
                        // Count all days EXCEPT Sunday (0) - Saturday is a work day
                        if (dayOfWeek !== 0) {
                            businessDays++;
                        }
                        current.setDate(current.getDate() + 1);
                    }

                    // Check if exceeds available days
                    if (businessDays > availableDays) {
                        alert('Los días solicitados (' + businessDays + ') exceden los días disponibles (' + availableDays + ')');
                        diasHabiles.value = availableDays;
                    } else {
                        diasHabiles.value = businessDays;
                    }
                }
            }

            if (fechaInicio && fechaFin) {
                fechaInicio.addEventListener('change', calculateBusinessDays);
                fechaFin.addEventListener('change', calculateBusinessDays);
            }
        });
    </script>
    @endpush

