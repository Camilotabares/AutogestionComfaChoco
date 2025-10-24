<x-admin-layout 
title="Dashboard"
:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=>route('admin.dashboard'),
    ],
    [
        'name'=>'Prueba',   
    ]
]">


    <x-wire-button>
        Click Me
    </x-wire-button>
</x-admin-layout>