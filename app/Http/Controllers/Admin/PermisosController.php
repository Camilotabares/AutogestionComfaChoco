<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permisos;
use Illuminate\Http\Request;


class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.permisos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.permisos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated= $request->validate([
        'tipo_permiso' => 'required|in:ausentismo,licencia',
        'tipo_de_ausentismo' => 'nullable|in:citas_medicas,permiso_personal,liciencia_luto,maternidad,paternidad',
        'fecha_inicio' => 'required|date',
        'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        'soporte' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);
    
         // Manejo del archivo si se subió
        if ($request->hasFile('soporte')) {
        $ruta = $request->file('soporte')->store('soportes', 'public');
        $validated['soporte'] = $ruta;
        }

        // Obtener el empleado del usuario autenticado
        $empleado = auth()->user()->empleado;
        if (!$empleado) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se encontró un empleado asociado a tu usuario',
            ]);
            return redirect()->back();
        }

        // Asignar estado por defecto y empleado_id
        $validated['estado'] = 'pendiente';
        $validated['empleado_id'] = $empleado->id;

        // Crear el permiso con los datos validados
        Permisos::create($validated);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Permiso creado con éxito',
            'text' => 'El permiso ha sido creado correctamente',
        ]);
        return redirect()->route('admin.permisos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permisos $permiso)
    { 
        return view('admin.permisos.show', compact('permiso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permisos $permiso)
    {
        return view('admin.permisos.edit', compact('permiso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permisos $permiso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permisos $permiso)
    {
        //
    }
}
