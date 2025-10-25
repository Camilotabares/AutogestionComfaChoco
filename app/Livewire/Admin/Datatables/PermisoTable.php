<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Permisos;

class PermisoTable extends DataTableComponent
{
    protected $model = Permisos::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Tipo de permiso", "tipo_de_permiso")
                ->sortable()
                ->searchable(),
            Column::make("Tipo de Ausentismo", "tipo_de_Ausentismo")
                ->sortable()
                ->searchable(),
            Column::make("Fecha de inicio", "fecha_inicio")
                ->sortable(),
            Column::make("Fecha final", "fecha_final")
                ->sortable(),
            Column::make("Estado", "estado")
                ->label(fn($row) => $row->estado === 'aprobado'
                    ? '<span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aprobado</span>'
                    : '<span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">No aprobado</span>'
                )
                ->html()
                ->sortable(),
                Column::make("Soporte")
                ->label(fn($row) => $row->soporte
                    ? '<a href="' . asset('storage/' . $row->soporte) . '" target="_blank" class="text-blue-600 underline">Ver</a>'
                    : 'Sin archivo'
                )
                ->html(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
