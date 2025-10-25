<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Empleado;

class EmpleadoTable extends DataTableComponent
{
    protected $model = Empleado::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Cédula", "cedula")
                ->sortable()
                ->searchable(),
            Column::make("Nombre", "nombre")
                ->sortable()
                ->searchable(),
            Column::make("Área", "area")
                ->sortable()
                ->searchable(),
            Column::make("Fecha de ingreso", "fecha_de_ingreso")
                ->sortable()
                ->searchable(),
            Column::make("Acciones")
                    ->label(function($row){
                    return view('admin.empleados.actions',[
                        'empleado'=>$row
                    ]);
                }), 
        ];
    }
}
