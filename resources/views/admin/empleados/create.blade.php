<x-admin-layout
title="Empleados"
:breadcrumbs="[
    [
        'name'=>'dashboard',
        'href'=>route('admin.dashboard'),
    ],
    [
        'name'=>'empleados',
        'href'=>route('admin.empleados.index'),
    ],
    [
        'name'=>'Crear',
    ],
]">

<x-wire-card>
    <form action="{{route('admin.empleados.store')}}" method="POST" >
        @csrf

        <div class="space-y-4">

            <div class="grid lg:grid-cols-2 gap-4 ">
                <x-wire-input
                label="Cedula"
                name="cedula"
                placeholder="Ingrese la cedula"
                value="{{old('cedula')}}"
                />
                <x-wire-input
                label="Nombre"
                name="nombre"
                placeholder="Ingrese el nombre"
                value="{{old('nombre')}}"
                />
                <x-wire-input
                label="Email"
                name="email"
                type="email"
                required
                placeholder="Ingrese el email"
                value="{{old('email')}}"
                />
                <x-wire-input
                name="password"
                label="Password"
                type="password"
                placeholder="Ingrese la contraseña"
                value="{{old('password')}}"
                />
                <x-wire-input
                name="password_confirmation"
                label="Confirmar Password"
                type="password"
                placeholder="Confirme la contraseña"
                value="{{old('password_confirmation')}}"
                />
                <x-wire-select
                label="Area"
                wire:model="area"
                placeholder="Seleccione el area"
                :options="[
                    ['name' => 'Administrativa','id'=>'administrativa'],
                    ['name' => 'Operativa', 'id'=>'operativa'],
                    ['name' => 'Comercial', 'id'=>'comercial'],
                    ['name' => 'TalentoHumano','id'=>'talentoHumano'],
                ]"
                option-label="name"
                option-value="id"
                />
                <x-wire-native-select
                    label="Rol"
                    name="role_id"
                    >
                <option value="">
                        Seleccione un rol
                    </option>
                    @foreach ( $roles as $role )
                        <option value="{{ $role->id }}"
                            @selected(old('role_id') == $role->id)
                            >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input
                label="fecha de ingreso"
                name="fecha_de_ingreso"
                placeholder="Ingrese la fecha de ingreso"
                value="{{old('fecha_ingreso')}}"
                />
        </div>

        </div>
        <x-wire-button blue class="flex justify-end mt-6 " type="submit">
            <i class="fa-solid fa-floppy-disk"></i> Guardar
        </x-wire-button>
    </form>
</x-wire-card>
</x-admin-layout>