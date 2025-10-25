<x-admin-layout
title="Empleados"
:breadcrumbs="[
    [
        'name'=>'dashboard',
        'href'=>route('admin.dashboard'),
    ],
    [
        'name'=>'empleados',
        'href'=>route('admin.empleados.index'),
    ],
    [
        'name'=>'Crear',
    ],
]">

<x-wire-card>
    <form action="{{route('admin.empleados.store')}}" method="POST" >
        @csrf
        <div class="">
            <x-wire-input
            label="Cedula"
            name="cedula"
            placeholder="Ingrese la cedula"
            value="{{old('cedula')}}"
            />
            <x-wire-input
            label="Nombre"
            name="nombre"
            placeholder="Ingrese el nombre"
            value="{{old('nombre')}}"
            />
            <x-wire-input
            label="Area"
            name="area"
            placeholder="Ingrese el area"
            value="{{old('area')}}"
            />
            <x-wire-input
            label="fecha de ingreso"
            name="fecha_de_ingreso"
            placeholder="Ingrese la fecha de ingreso"
            value="{{old('fecha_ingreso')}}"
            />
            <x-wire-button blue class="flex justify-end mt-6 " type="submit">
                <i class="fa-solid fa-floppy-disk"></i> Guardar
            </x-wire-button>
        </div>
    </form>
</x-wire-card>
</x-admin-layout>