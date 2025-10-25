<x-admin-layout 
title="Dashboard"
:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=>route('admin.dashboard'),
    ]
]">

    {{-- Mensaje de Bienvenida Simple --}}
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">
            Bienvenido a ComfaChoco
        </h1>
        <p class="text-gray-600 mt-2">
            {{ auth()->user()->name }}
        </p>
    </div>

</x-admin-layout>
