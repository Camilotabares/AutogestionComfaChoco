<x-admin-layout 
title="Dashboard"
:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=>route('admin.dashboard'),
    ]
]">

    {{-- Mensaje de Bienvenida --}}
    <div class="card max-w-4xl mx-auto mt-8 text-center">
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full mb-4">
                <i class="fa-solid fa-user-tie text-3xl text-white"></i>
            </div>
        </div>
        <h1 class="text-4xl font-bold text-primary-700 mb-2">
            Bienvenido a Sinet
        </h1>
        <p class="text-neutral-600 text-lg mb-6">
            {{ auth()->user()->name }}
        </p>
        <div class="border-t border-neutral-200 pt-6 mt-6">
            <p class="text-sm text-neutral-500">
                Sistema de Autogestión - Gestiona tus vacaciones, permisos y solicitudes de forma rápida y sencilla.
            </p>
        </div>
    </div>

</x-admin-layout>
