<?php

namespace App\Http\Controllers\Admin;

use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        $roles = Role::all();
        return view('admin.empleados.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cedula' => 'required|unique:empleados,cedula',
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email||max:255|unique:empleados',
            'password' => 'required|string|min:8|confirmed',
            'area' => 'required|in:administrativa,Operativa,Comercial,TalentoHumano',
            'role_id' => 'required|exists:roles,id',
            'fecha_de_ingreso' => 'required|date_format:Y-m-d',
        ]);


        $empleado = Empleado::create($data);

        $empleado->roles()->attach($data['role_id']);


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
