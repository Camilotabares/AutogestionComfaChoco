<x-admin-layout
    title="{{ __('Roles') }}"
    :breadcrumbs="[
        [
            'name' => __('Dashboard'),
            'route' => route('admin.dashboard'),
        ],
        [
            'name' => __('Roles'),
            'route' => route('admin.roles.index'),
        ],
        [
            'name'=>'Nuevo'
        ],
    ]"
>
    <div class="card mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-primary-700">{{ __('Roles') }}</h1>
                <p class="text-neutral-600 mt-1">
                    {{ __('Gestiona roles y asigna permisos a los usuarios del sistema.') }}
                </p>
            </div>
            @can('roles.create')
                <x-wire-button primary href="{{ route('admin.roles.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus mr-2"></i>
                    {{ __('Crear Rol') }}
                </x-wire-button>
            @endcan
        </div>
    </div>

    @livewire('admin.datatables.role-table')
</x-admin-layout>
