<x-admin-layout
    title="{{ __('Vacaciones') }}"
>
    @php
        $tabs = [
            ['key' => 'solicitar', 'label' => __('Solicitar')],
            ['key' => 'consultas', 'label' => __('Consultas')],
            // ['key' => 'gestion-equipo', 'label' => __('Gestión Vacaciones de Equipo')],
        ];

        $currentTab = request('tab', 'solicitar');
        $validTabs = collect($tabs)->pluck('key')->toArray();

        if (! in_array($currentTab, $validTabs, true)) {
            $currentTab = 'solicitar';
        }
    @endphp

    <div class="max-w-6xl mx-auto space-y-6 px-4">
        @if (session('status'))
            <div class="p-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded">
                {{ session('status') }}
            </div>
        @endif

    <nav class="grid grid-cols-2 gap-1 rounded-full overflow-hidden shadow border border-slate-200 bg-slate-100">
            @foreach ($tabs as $tab)
                <a
                    href="{{ route('admin.vacaciones.index', ['tab' => $tab['key']]) }}"
                    class="text-center text-xs sm:text-sm font-semibold uppercase tracking-wide py-2 sm:py-3 transition
                        {{ $currentTab === $tab['key']
                            ? 'bg-blue-600 text-white shadow'
                            : 'bg-white text-slate-600 hover:bg-blue-50 hover:text-blue-600' }}"
                >
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>

        @includeIf('admin.vacations.tabs.' . $currentTab)
    </div>
</x-admin-layout>
