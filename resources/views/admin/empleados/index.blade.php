<x-admin-layout 
title="Empleados"
:breadcrumbs="[
    [
        'name'=>'Empleados',
        'href'=>route('admin.empleados.index'),
    ],
    [
        'name'=>'Lista de empleados',   
    ]
]"> 

@can('empleados.create')
    <x-slot name="action">
        <x-wire-button primary href="{{ route('admin.empleados.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus mr-2"></i> Nuevo Empleado
        </x-wire-button>
    </x-slot>
@endcan

<div class="card mb-6">
    <h2 class="card-header">
        <i class="fa-solid fa-users"></i>
        {{ __('Gestión de Empleados') }}
    </h2>
    <p class="text-neutral-600 text-sm">
        Administra la información de los empleados del sistema.
    </p>
</div>

@livewire('admin.datatables.empleado-table')
</x-admin-layout>