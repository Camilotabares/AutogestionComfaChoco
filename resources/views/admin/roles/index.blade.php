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
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('Roles') }}</h1>
        <p class="text-gray-600">
            {{ __('Aquí podrás gestionar los roles cuando se definan las funciones correspondientes.') }}
        </p>
    </div>

    @livewire('admin.datatables.role-table')
</x-admin-layout>
