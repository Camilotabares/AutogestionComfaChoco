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

<x-wire-card>
    <form wire:submit.prevent="store" enctype="multipart/form-data">
         <div class="">
            <x-wire-select
            label="Tipo de Permiso"
            wire:model="tipo_permiso"
            placeholder="Seleccione el tipo de permiso"
            :options="[
                ['name'=>'Ausentismo', 'id'=>'ausentismo'],
                ['name'=>'Licencia', 'id'=>'licencia'],
            ]"
            option-label="name"
            option-value="id"
            />
            <x-wire-select
            label="Tipo de Ausentismo"
            wire:model="tipo_de_ausentismo"
            placeholder="Seleccione el tipo de ausentismo"
            :options="[
                ['name'=> 'citas_medicas', 'id'=>'citas_medicas'],
                ['name'=> 'permiso_personal', 'id'=>'permiso_personal'],
                ['name'=> 'liciencia_luto', 'id'=>'liciencia_luto'],
                ['name'=> 'maternidad', 'id'=>'maternidad'],
                ['name'=> 'paternidad', 'id'=>'paternidad'],
            ]"
            option-label="name"
            option-value="id"
            />
            <x-wire-datetime-picker
            label="Fecha de inicio"
            name="fecha_inicio"
            without-time
            display-format="DD/MM/YYYY"
            required
            />
            <x-wire-datetime-picker
            label="Fecha de fin"
            name="fecha_fin"
            without-time
            display-format="DD/MM/YYYY"
            required
            />
            <x-wire-input
            label="Soporte del permiso"
            name="soporte"
            placeholder="Ingrese el soporte del permiso"
            type="file"
            />
        <div class="">

            <x-wire-button blue class="flex justify-end mt-6 " type="submit">
                <i class="fa-solid fa-floppy-disk"></i> Guardar
            </x-wire-button>
        </div>
    </form>
</x-wire-card>

</x-admin-layout>
