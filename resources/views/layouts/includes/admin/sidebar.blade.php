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
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
            @isset($link['header'])
                <div class="px-2 py-2 font-semibold text-gray-500 uppercase">
                    {{ $link['header'] }}
                </div>
            @else
            <li>
                <a href="{{ $link['href'] }}" class="flex items-center p-2 text-gray-600 rounded-lg dark:text-white hover:bg-gray-400 dark:hover:bg-gray-700 group {{ $link['active'] ? '' : ''}}">
                    <span class="inline-flex justify-center items-center w-6 h-6">
                        <i class="{{ $link['icon'] }}"></i>
                    </span>
                    <span class="ms-3">{{$link['name']}}</span>
                </a>
            </li>
            @endisset

            @endforeach
        </ul>
    </div>
</aside>