<?php

namespace App\Http\Controllers\Admin;

use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.empleados.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:empleados,cedula',
            'nombre' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'fecha_de_ingreso' => 'required|date_format:Y-m-d',
        ]);

        Empleado::create([
            'cedula' => $request->cedula,
            'nombre' => $request->nombre,
            'area' => $request->area,
            'fecha_de_ingreso' => $request->fecha_de_ingreso,
        ]);


        session()->flash('swal',[
            'icon'=>'success',
            'title'=>'Empleado creado con exito',
            'text'=>'El empleado ha sido creado correctamente'
        ]);


        return redirect()->route('admin.empleados.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        return view('admin.empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        return view('admin.empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'cedula' => 'required|unique:empleados,cedula,' . $empleado->id,
            'nombre' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'fecha_de_ingreso' => 'required|date_format:Y-m-d',
        ]);

        $empleado->update([
            'cedula' => $request->cedula,
            'nombre' => $request->nombre,
            'area' => $request->area,
            'fecha_de_ingreso' => $request->fecha_de_ingreso,
        ]);

        session()->flash('swal',[
            'icon'=>'success',
            'title'=>'Empleado actualizado con exito',
            'text'=>'El empleado ha sido actualizado correctamente'
        ]);

        return redirect()->route('admin.empleados.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        {
            $empleado->delete();
    
            session()->flash('swal',[
                'icon'=>'success',
                'title'=>'Cliente eliminado con exito',
                'text'=>'El cliente ha sido eliminado correctamente'
            ]);
            return redirect()->route('admin.empleados.index');
        }
    }
}
