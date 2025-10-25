<x-admin-layout 
title="Permisos"
:breadcrumbs="[
    [
        'name'=>'Permisos',
        'href'=>route('admin.permisos.index'),
    ],
    [
        'name'=>'Registrar Ausentismo',   
    ]
]"> 
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('Bienvenido ') }} {{ auth()->user()->name }}</h1>
        <p class="text-gray-600">
            {{ __('Desde este panel puedes registrar y consultar solicitudes de ausentismo o licencia. Una vez enviadas, serán revisadas por el área de RRHH para su aprobación.') }}
        </p>
        <x-slot name="action">
            <x-wire-button  blue href="{{ route('admin.permisos.create') }}" >
                <i class="fa-solid fa-plus"></i> Registrar Ausentismo
            </x-wire-button>
        </x-slot>
    </div>


</x-admin-layout>