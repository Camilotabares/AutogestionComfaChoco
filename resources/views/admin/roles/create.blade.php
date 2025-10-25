<x-admin-layout 
title="Dashboard"
:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=>route('admin.dashboard'),
    ],
    [
        'name'=>'Roles',   
    ]
]">


    <x-wire-card>
        <h1 class="text-2xl font-semibold mb-4 text-gray-900">
            Nuevo rol
        </h1>
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-wire-input
                label="Nombre del rol"
                name="name"
                placeholder="Ingrese el nombre del rol"
                value="{{ old('name') }}"
                required
            />
            <div>
                <p class="text-sm text-gray-600 mb-2 font-semibold">
                    Permisos
                </p>
            </div>
            <ul class="columns-1 md:columns-2 lg:columns-4 gap-4">
                @foreach ($permissions as $permission)
                    <li class="mb-2">
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->id }}"
                                class="form-checkbox h-5 w-5 text-primary-600"
                                {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                            />
                            <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
            <div>
                <x-wire-button primary type="submit" blue>
                    Crear Rol
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>