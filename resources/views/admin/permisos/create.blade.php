<x-admin-layout 
title="Permisos"
:breadcrumbs="[
    [
        'name'=>'Permisos',
        'href'=>route('admin.permisos.index'),
    ],
    [
        'name'=>'Tramitar permiso',   
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

<x-wire-card>
    <form method="POST" action="{{ route('admin.permisos.store') }}" enctype="multipart/form-data" id="permisoForm">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="tipo_permiso" class="block text-sm font-medium text-gray-700">Tipo de Permiso</label>
                <select name="tipo_permiso" id="tipo_permiso" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Seleccione el tipo de permiso</option>
                    <option value="ausentismo">Ausencia</option>
                    <option value="licencia">Licencia</option>
                </select>
                @error('tipo_permiso')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="tipo_de_ausentismo" class="block text-sm font-medium text-gray-700">Tipo de Ausencia</label>
                <select name="tipo_de_ausentismo" id="tipo_de_ausentismo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">Seleccione el tipo de ausencia</option>
                    <option value="citas_medicas">Citas Médicas</option>
                    <option value="permiso_personal">Permiso Personal</option>
                    <option value="liciencia_luto">Licencia de Luto</option>
                    <option value="maternidad">Maternidad</option>
                    <option value="paternidad">Paternidad</option>
                </select>
                @error('tipo_de_ausentismo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" required min="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                @error('fecha_inicio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="fecha_final" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <input type="date" name="fecha_final" id="fecha_final" required min="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                @error('fecha_final')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="dias_habiles" class="block text-sm font-medium text-gray-700">Días Hábiles</label>
                <input type="number" name="dias_habiles" id="dias_habiles" min="1" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50" placeholder="Ingrese los días hábiles">
                <p class="text-xs text-gray-500 mt-1">Ingrese manualmente los días hábiles del permiso (debe ser menor o igual a la duración total)</p>
                <span id="diasHabilesError" class="text-red-500 text-sm hidden"></span>
                @error('dias_habiles')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="soporte" class="block text-sm font-medium text-gray-700">Soporte del Permiso</label>
                <input type="file" name="soporte" id="soporte" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('soporte')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar
                </button>
            </div>
        </div>
    </form>
</x-wire-card>

@push('js')
<script>
    // Validar días hábiles en tiempo real
    function validarDiasHabiles() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFinal = document.getElementById('fecha_final').value;
        const diasHabiles = parseInt(document.getElementById('dias_habiles').value);
        const errorSpan = document.getElementById('diasHabilesError');
        const submitBtn = document.getElementById('submitBtn');

        if (fechaInicio && fechaFinal && diasHabiles) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFinal);
            const diffTime = Math.abs(fin - inicio);
            const diasTotales = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            if (diasHabiles > diasTotales) {
                errorSpan.textContent = `Los días hábiles (${diasHabiles}) no pueden ser mayores a la duración total (${diasTotales} días).`;
                errorSpan.classList.remove('hidden');
                submitBtn.disabled = true;
                return false;
            } else {
                errorSpan.classList.add('hidden');
                submitBtn.disabled = false;
                return true;
            }
        }
        return true;
    }

    document.getElementById('fecha_inicio').addEventListener('change', validarDiasHabiles);
    document.getElementById('fecha_final').addEventListener('change', validarDiasHabiles);
    document.getElementById('dias_habiles').addEventListener('input', validarDiasHabiles);

    document.getElementById('permisoForm').addEventListener('submit', function(e) {
        if (!validarDiasHabiles()) {
            e.preventDefault();
            return false;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Guardando...';
    });
</script>
@endpush

</x-admin-layout>
