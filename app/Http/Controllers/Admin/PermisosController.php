<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permisos;
use Illuminate\Http\Request;


class PermisosController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permisos.index')->only(['index', 'show']);
        $this->middleware('permission:permisos.create')->only(['create', 'store']);
        $this->middleware('permission:permisos.edit')->only(['edit', 'update']);
        $this->middleware('permission:permisos.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $empleado = $user->empleado;
        
        // Si el usuario no tiene empleado, mostrar colecciones vacías
        if (!$empleado) {
            return view('admin.permisos.index', [
                'pendientes' => collect(),
                'aprobados' => collect(),
                'rechazados' => collect(),
            ]);
        }
        
        // Cargar solo las solicitudes del empleado actual
        $pendientes = Permisos::where('empleado_id', $empleado->id)
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $aprobados = Permisos::where('empleado_id', $empleado->id)
            ->where('estado', 'aprobado')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $rechazados = Permisos::where('empleado_id', $empleado->id)
            ->where('estado', 'rechazado')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.permisos.index', compact('pendientes', 'aprobados', 'rechazados'));
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
        'fecha_inicio' => 'required|date|after_or_equal:today',
        'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        'dias_habiles' => [
            'required',
            'integer',
            'min:1',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->fecha_inicio && $request->fecha_final) {
                    $inicio = \Carbon\Carbon::parse($request->fecha_inicio);
                    $fin = \Carbon\Carbon::parse($request->fecha_final);
                    $diasTotales = $inicio->diffInDays($fin) + 1;
                    
                    if ($value > $diasTotales) {
                        $fail("Los días hábiles ($value) no pueden ser mayores a la duración total del permiso ($diasTotales días).");
                    }
                }
            }
        ],
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

    public function approve(string $id)
    {
        $permiso = Permisos::findOrFail($id);
        $user = auth()->user();

        // Solo se pueden aprobar permisos pendientes
        if ($permiso->estado !== 'pendiente') {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Ya procesado',
                'text' => 'Este permiso ya fue procesado.',
            ]);
            return redirect()->route('admin.permisos.index');
        }

        // Si es supervisor y el permiso es mayor a 2 días, no puede aprobar
        if ($user->hasRole('supervisor') && !$user->hasAnyRole(['rrhh', 'admin']) && $permiso->dias_habiles > 2) {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Sin autorización',
                'text' => 'Los permisos mayores a 2 días deben ser aprobados por Recursos Humanos.',
            ]);
            return redirect()->route('admin.solicitudes-pendientes.index');
        }

        // Actualizar estado
        $permiso->update(['estado' => 'aprobado']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Aprobado',
            'text' => 'El permiso ha sido aprobado correctamente.',
        ]);

        return redirect()->route('admin.solicitudes-pendientes.index');
    }

    public function reject(string $id)
    {
        $permiso = Permisos::findOrFail($id);
        $user = auth()->user();

        // Solo se pueden rechazar permisos pendientes
        if ($permiso->estado !== 'pendiente') {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Ya procesado',
                'text' => 'Este permiso ya fue procesado.',
            ]);
            return redirect()->route('admin.solicitudes-pendientes.index');
        }

        // Si es supervisor y el permiso es mayor a 2 días, no puede rechazar
        if ($user->hasRole('supervisor') && !$user->hasAnyRole(['rrhh', 'admin']) && $permiso->dias_habiles > 2) {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Sin autorización',
                'text' => 'Los permisos mayores a 2 días deben ser procesados por Recursos Humanos.',
            ]);
            return redirect()->route('admin.solicitudes-pendientes.index');
        }

        // Actualizar estado
        $permiso->update(['estado' => 'rechazado']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rechazado',
            'text' => 'El permiso ha sido rechazado.',
        ]);

        return redirect()->route('admin.solicitudes-pendientes.index');
    }
}
