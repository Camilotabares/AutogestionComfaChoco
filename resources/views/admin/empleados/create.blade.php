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
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
                <x-wire-native-select
                    label="Area"
                    name="area"
                    required
                >
                    <option value="">Seleccione un área</option>
                    <option value="administrativa" @selected(old('area') == 'administrativa')>Administrativa</option>
                    <option value="operativa" @selected(old('area') == 'operativa')>Operativa</option>
                    <option value="comercial" @selected(old('area') == 'comercial')>Comercial</option>
                    <option value="talentoHumano" @selected(old('area') == 'talentoHumano')>Talento Humano</option>
                </x-wire-native-select>

                <x-wire-native-select
                    label="Rol"
                    name="role_id"
                    required
                >
                    <option value="">Seleccione un rol</option>
                    @foreach ( $roles as $role )
                        <option value="{{ $role->id }}"
                            @selected(old('role_id') == $role->id)
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <x-wire-input
                    label="Fecha de ingreso"
                    name="fecha_de_ingreso"
                    type="date"
                    required
                    placeholder="YYYY-MM-DD"
                    value="{{old('fecha_de_ingreso')}}"
                />
        </div>

        </div>
        <x-wire-button blue class="flex justify-end mt-6 " type="submit">
            <i class="fa-solid fa-floppy-disk"></i> Guardar
        </x-wire-button>
    </form>
</x-wire-card>
</x-admin-layout>