<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudVacacion;
use App\Models\Permisos;
use Illuminate\Http\Request;

class SolicitudesPendientesController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Solo RRHH, Supervisor y Admin pueden ver esta vista
        if (!$user->hasAnyRole(['rrhh', 'supervisor', 'admin'])) {
            abort(403, 'No tienes autorización para ver esta página.');
        }

        // Obtener solicitudes de vacaciones pendientes
        $vacacionesPendientesQuery = SolicitudVacacion::where('estado', 'pendiente')
            ->orderBy('created_at', 'desc');

        // Obtener permisos pendientes
        $permisosPendientesQuery = Permisos::where('estado', 'pendiente')
            ->with('empleado')
            ->orderBy('created_at', 'desc');

        // Filtrar según el rol
        if ($user->hasRole('supervisor') && !$user->hasAnyRole(['rrhh', 'admin'])) {
            // Supervisor solo ve solicitudes de ≤ 2 días
            $vacacionesPendientes = $vacacionesPendientesQuery->get()->filter(function($solicitud) {
                return $solicitud->dias_habiles <= 2;
            })->values();

            $permisosPendientes = $permisosPendientesQuery->get()->filter(function($permiso) {
                return $permiso->dias_habiles <= 2;
            })->values();
        } else {
            // RRHH y Admin ven solicitudes de > 2 días
            $vacacionesPendientes = $vacacionesPendientesQuery->get()->filter(function($solicitud) {
                return $solicitud->dias_habiles > 2;
            })->values();

            $permisosPendientes = $permisosPendientesQuery->get()->filter(function($permiso) {
                return $permiso->dias_habiles > 2;
            })->values();
        }

        return view('admin.solicitudes-pendientes.index', compact('vacacionesPendientes', 'permisosPendientes'));
    }
}
