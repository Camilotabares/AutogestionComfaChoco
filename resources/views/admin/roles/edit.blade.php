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
            'name'=>'Editar'
        ],
    ]"
>
        <x-wire-card>
            <h1>Editar rol</h1>
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
    
                <x-wire-input
                    label="Nombre del rol"
                    name="name"
                    placeholder="Ingrese el nombre del rol"
                    value="{{ old('name', $role->name) }}"
                    required
                />
    
                <div>
                    <p class="text-sm text-gray-600 mb-2 font-semibold">
                        Permisos
                    </p>
                    <ul class="columns-1 md:columns-2 lg:columns-4 gap-4">
                        @foreach ($permissions as $permission)
                            <li class="mb-2">
                                <label class="inline-flex items-center">
                                    <x-checkbox
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        class="form-checkbox h-5 w-5 text-primary-600"
                                        :checked="in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))"
                                    >
                                    <span class="ml-2 text-gray-700">{{ $permission->name }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
    
                <div>
                    <x-wire-button primary type="submit" blue>
                        Actualizar Rol
                    </x-wire-button>
                </div>
            </form>
</x-admin-layout>
