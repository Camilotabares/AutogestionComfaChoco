<x-admin-layout 
title="empleados"
:breadcrumbs="[
    [
        'name'=>'Empelados',
        'href'=>route('admin.empleados.index'),
    ],
    [
        'name'=>'Lista de empleados',   
    ]
]"> 

<x-slot name="action">
    <x-wire-button  blue href="{{ route('admin.empleados.create') }}" >
        <i class="fa-solid fa-plus"></i> Nuevo Empleado
    </x-wire-button>
</x-slot>

@livewire('admin.datatables.empleado-table')
</x-admin-layout>