@php
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'header'=>'Gestion'
        ],
    ];

    // Solo mostrar Roles si tiene permiso
    if (auth()->user()->can('roles.index')) {
        $links[] = [
            'name' => 'Roles',
            'icon' => 'fa-solid fa-user-tie',
            'href' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),
        ];
    }

    // Solo mostrar Empleados si tiene permiso
    if (auth()->user()->can('empleados.index')) {
        $links[] = [
            'name' => 'Empleados',
            'icon' => 'fa-solid fa-user-tie',
            'href' => route('admin.empleados.index'),
            'active' => request()->routeIs('admin.empleados.*'),
        ];
    }

    // Solicitudes Pendientes - solo para RRHH, Supervisor, Admin
    if (auth()->user()->hasAnyRole(['rrhh', 'supervisor', 'admin'])) {
        $links[] = [
            'name' => 'Solicitudes Pendientes',
            'icon' => 'fa-solid fa-clock',
            'href' => route('admin.solicitudes-pendientes.index'),
            'active' => request()->routeIs('admin.solicitudes-pendientes.*'),
        ];
    }

    // Solicitudes - todos pueden ver
    $links[] = [
        'name' => 'Solicitudes',
        'icon' => 'fa-solid fa-box-tissue',
        'href' => route('admin.dashboard'),
        'active' => request()->routeIs('admin.vacaciones.*'),
        'submenu' => [
            [
                'name' => 'Vacaciones',
                'href' => route('admin.vacaciones.index'),
                'active' => request()->routeIs('admin.vacaciones.index'),
            ],
            [
                'name' => 'Permisos',
                'href' => route('admin.permisos.index'),
                'active' => request()->routeIs('admin.permisos.*'),
            ],
        ],
    ];
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-gradient-to-b from-white to-neutral-50 border-r border-neutral-200 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
            <li>
                @isset($link['header'])
                        <div class="px-3 py-2 text-xs font-bold text-neutral-500 uppercase tracking-wider mt-4">
                            {{ $link['header'] }}
                        </div>
                    @else
                        @isset($link['submenu'])
                            <button type="button" class="flex items-center w-full p-3 text-neutral-700 transition rounded-lg group hover:bg-primary-50 hover:text-primary-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                                    <span class="inline-flex justify-center items-center w-5 h-5">
                                        <i class="{{ $link['icon'] }}"></i>
                                    </span>
                                    <span class="flex-1 ms-3 text-left text-sm font-medium">{{$link['name']}}</span>
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                    </svg>
                                </button>
                                <ul id="dropdown-example" class="hidden py-2 space-y-1">
                                    @foreach ($link['submenu'] as $item)
                                    <li>
                                        <a href="{{$item['href']}}" class="flex items-center w-full p-2 pl-11 text-sm text-neutral-600 transition rounded-lg hover:bg-primary-50 hover:text-primary-700 {{ $item['active'] ? 'bg-primary-100 text-primary-700 font-semibold' : '' }}">
                                            {{$item['name']}}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                        @else
                            <a href="{{ $link['href'] }}" class="flex items-center p-3 text-neutral-700 rounded-lg transition hover:bg-primary-50 hover:text-primary-700 group {{ $link['active'] ? 'bg-primary-100 text-primary-700 font-semibold shadow-sm' : ''}}">
                                <span class="inline-flex justify-center items-center w-5 h-5">
                                    <i class="{{ $link['icon'] }}"></i>
                                </span>
                                <span class="ms-3 text-sm font-medium">{{$link['name']}}</span>
                            </a>
                        @endisset
                @endisset
            </li>
            @endforeach
        </ul>
    </div>
</aside>
