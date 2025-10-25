<x-admin-layout 
title="empleados"
:breadcrumbs="[
    [
        'name'=>'Empelados',
        'href'=>route('admin.empleados.index'),
    ],
    [
        'name'=>'Crear empleado',   
    ],
    [
        'name'=>'Lista de empleados',   
    ]
]"> 
<x-wire-card>
    <form action="{{route('admin.empleados.update',$empleado)}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="">
            <x-wire-input
            label="Cedula"
            name="cedula"
            placeholder="Ingrese la cedula"
            value="{{old('cedula',$empleado->cedula)}}"
            />
            <x-wire-input
            label="Nombre"
            name="nombre"
            placeholder="Ingrese el nombre"
            value="{{old('nombre',$empleado->nombre)}}"
            />
            <x-wire-input
            label="Area"
            name="area"
            placeholder="Ingrese el area"
            value="{{old('area',$empleado->area)}}"
            />
            <x-wire-input
            label="fecha de ingreso"
            name="fecha_de_ingreso"
            placeholder="Ingrese la fecha de ingreso"
            value="{{old('fecha_ingreso',$empleado->fecha_de_ingreso)}}"
            />
            <x-wire-button blue class="flex justify-end mt-6 " type="submit">
                <i class="fa-solid fa-floppy-disk"></i> Actualizar
            </x-wire-button>
        </div>
    </form>
</x-wire-card>
</x-admin-layout>