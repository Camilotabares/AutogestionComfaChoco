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
                        <div class="text-2xl font-semibold text-blue-700">{{ $accrued_days ?? 0 }}</div>
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

                <div class="grid gap-4">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <x-wire-input 
                            id="fecha_inicio"
                            label="{{ __('Desde') }}" 
                            type="date" 
                            name="fecha_inicio" 
                            :value="old('fecha_inicio')" 
                            @if(isset($min_start_date)) min="{{ $min_start_date->format('Y-m-d') }}" @endif 
                            onchange="calculateBusinessDays()"
                            required 
                        />
                        <x-wire-input 
                            id="fecha_fin"
                            label="{{ __('Hasta') }}" 
                            type="date" 
                            name="fecha_fin" 
                            :value="old('fecha_fin')" 
                            onchange="calculateBusinessDays()"
                            required 
                        />
                        <x-wire-input 
                            id="dias_habiles"
                            label="{{ __('No. Días Hábiles') }}" 
                            type="number" 
                            min="0" 
                            max="{{ $days_available ?? 0 }}"
                            name="dias_habiles" 
                            :value="old('dias_habiles')" 
                            readonly
                            required 
                        />
                    </div>

                    <script>
                        function calculateBusinessDays() {
                            const startDate = document.getElementById('fecha_inicio').value;
                            const endDate = document.getElementById('fecha_fin').value;
                            const availableDays = {{ $days_available ?? 0 }};

                            if (startDate && endDate) {
                                const start = new Date(startDate);
                                const end = new Date(endDate);
                                let businessDays = 0;
                                const current = new Date(start);

                                while (current <= end) {
                                    const dayOfWeek = current.getDay();
                                    // Count only Monday (1) through Friday (5)
                                    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                                        businessDays++;
                                    }
                                    current.setDate(current.getDate() + 1);
                                }

                                // Limit to available days
                                businessDays = Math.min(businessDays, availableDays);
                                document.getElementById('dias_habiles').value = businessDays;
                            }
                        }
                    </script>

                    {{-- Autorizador removed --}}

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

