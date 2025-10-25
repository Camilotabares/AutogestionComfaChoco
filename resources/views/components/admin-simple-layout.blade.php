@props([
    'title' => config('app.name', 'Auto Gestion ComfaCho'),
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <wireui:scripts />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://kit.fontawesome.com/2f13018d2f.js" crossorigin="anonymous"></script>
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @include('layouts.includes.admin.navigation')

        <main class="pt-20">
            {{ $slot }}
        </main>

        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

        @if (session('swal'))
            <script>
                Swal.fire(@json(session('swal')));
            </script>
        @endif
    </body>
</html>
