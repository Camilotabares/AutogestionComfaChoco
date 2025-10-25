<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\SolicitudVacacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Vacacion;

class VacationController extends Controller
{
    private const AREAS = [
        'Administrativa',
        'Operativa',
        'Comercial',
        'Talento Humano',
    ];

    public function index(\Illuminate\Http\Request $request)
    {
        $usuario = Auth::user();

        $empleado = Empleado::where('user_id', $usuario->id)->first();
        $solicitudes = collect();
        $pendientes = collect();
        $years = collect();
        // Defaults for vacation calculations
        $years_completed = 0;
        $has_one_year = false;
        $last_anniversary = null;
        $min_start_date = null;
        $accrued_days = 0;
        $days_taken = 0;
        $days_available = 0;
        $can_request = false;

        // Si es RRHH, Supervisor o Admin, mostrar TODAS las solicitudes pendientes
        if ($usuario->hasAnyRole(['rrhh', 'supervisor', 'admin'])) {
            $pendientes = SolicitudVacacion::where('estado', 'pendiente')
                ->orderByDesc('created_at')
                ->get();
        }

        if ($empleado) {
            $baseQuery = SolicitudVacacion::query()
                ->where('cedula', $empleado->cedula);

            // obtener años únicos desde las fechas de las solicitudes (created_at)
            $years = $baseQuery->get()
                ->pluck('created_at')
                ->filter()
                ->map(fn($d) => $d->format('Y'))
                ->unique()
                ->sort()
                ->values();

            // aplicar filtros por query string: estado y year
            $solicitudes = $baseQuery
                ->when($request->filled('estado'), fn($q) => $q->where('estado', $request->input('estado')))
                ->when($request->filled('year'), fn($q) => $q->whereYear('created_at', $request->input('year')))
                ->orderByDesc('created_at')
                ->get();

            // Si es empleado, solo sus propias pendientes
            if (!$usuario->hasAnyRole(['rrhh', 'supervisor', 'admin'])) {
                $pendientes = $solicitudes->where('estado', 'pendiente')->values();
            }

            // --- Vacation eligibility and balances ---
            if ($empleado->fecha_de_ingreso) {
                $fechaIngreso = Carbon::parse($empleado->fecha_de_ingreso)->startOfDay();
                $today = Carbon::today();

                // full years completed since fecha de ingreso
                $years_completed = $fechaIngreso->diffInYears($today);
                $has_one_year = $years_completed >= 1;

                // last anniversary (most recent completed anniversary)
                $last_anniversary = $fechaIngreso->copy()->addYears($years_completed);

                // accrued days: 15 per year, capped at 30
                $accrual_years = min($years_completed, 2);
                $accrued_days = (int) ($accrual_years * 15);

                // minimum allowed start date is one month after the last anniversary
                // BUT if the employee already has the maximum accrued days (30),
                // we do not apply the anniversary-based restriction.
                if ($accrued_days >= 30) {
                    $min_start_date = null;
                } else {
                    $min_start_date = $last_anniversary->copy()->addMonth();
                }

                // days taken: sum of approved solicitudes (these are already taken/confirmed)
                $days_taken = (int) SolicitudVacacion::where('cedula', $empleado->cedula)
                    ->where('estado', 'aprobado')
                    ->sum('dias_habiles');

                // days requested but pending: sum of pending solicitudes only
                $days_pending = (int) SolicitudVacacion::where('cedula', $empleado->cedula)
                    ->where('estado', 'pendiente')
                    ->sum('dias_habiles');

                // Available days = accrued - taken - pending
                $days_available = (int) max(0, $accrued_days - $days_taken - $days_pending);

                // Available days = accrued - taken - pending
                $days_available = (int) max(0, $accrued_days - $days_taken - $days_pending);

                // can request if has completed 1 year and has available days
                if (! $has_one_year) {
                    $can_request = false;
                } elseif ($days_available <= 0) {
                    $can_request = false;
                } else {
                    $can_request = true;
                }
            }
        }

        $areas = self::AREAS;

        $selectedEstado = $request->input('estado');
        $selectedYear = $request->input('year');

        return view('admin.vacations.index', compact(
            'usuario',
            'empleado',
            'solicitudes',
            'pendientes',
            'areas',
            'years',
            'selectedEstado',
            'selectedYear',
            'years_completed',
            'has_one_year',
            'last_anniversary',
            'min_start_date',
            'accrued_days',
            'days_taken',
            'days_available',
            'can_request'
        ));
    }

    // Método eliminado ya que no se necesita el registro de información personal

    public function store(Request $request)
    {
        $empleado = Empleado::where('user_id', Auth::id())->first();

        if (! $empleado || ! $empleado->fecha_de_ingreso) {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'solicitar'])
                ->with('status', __('No se encontró la información de ingreso. Contacte a RRHH.'));
        }

        // Get available days first for validation
        $fechaIngreso = Carbon::parse($empleado->fecha_de_ingreso)->startOfDay();
        $today = Carbon::today();
        $years_completed = $fechaIngreso->diffInYears($today);
        $accrued_days = (int) (min($years_completed, 2) * 15);
        
        // Days taken: approved solicitudes
        $days_taken = (int) SolicitudVacacion::where('cedula', $empleado->cedula)
            ->where('estado', 'aprobado')
            ->sum('dias_habiles');
        
        // Days pending: pending solicitudes only
        $days_pending = (int) SolicitudVacacion::where('cedula', $empleado->cedula)
            ->where('estado', 'pendiente')
            ->sum('dias_habiles');
        
        $days_available = (int) max(0, $accrued_days - $days_taken - $days_pending);

        $validated = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'dias_habiles' => [
                'required', 
                'integer', 
                'min:0',
                function($attribute, $value, $fail) use ($days_available) {
                    if ($value > $days_available) {
                        $fail(__('El número de días hábiles no puede ser mayor a los días disponibles (:days)', ['days' => $days_available]));
                    }
                }
            ],
            'observaciones' => ['nullable', 'string'],
        ]);

        // Recompute eligibility
        $fechaIngreso = Carbon::parse($empleado->fecha_de_ingreso)->startOfDay();
        $today = Carbon::today();
        $years_completed = $fechaIngreso->diffInYears($today);
        $has_one_year = $years_completed >= 1;
        $last_anniversary = $fechaIngreso->copy()->addYears($years_completed);
        $min_start_date = $last_anniversary->copy()->addMonth();

        $accrued_days = min($years_completed, 2) * 15;
        // If the employee already has the maximum accrual (30 days),
        // we should not enforce the anniversary + 1 month minimum start date.
        if ($accrued_days >= 30) {
            $min_start_date = null;
        } else {
            $min_start_date = $fechaIngreso->copy()->addYears($years_completed)->addMonth();
        }
        // Days taken: approved solicitudes
        $days_taken = SolicitudVacacion::where('cedula', $empleado->cedula)
            ->where('estado', 'aprobado')
            ->sum('dias_habiles');
        
        // Days pending: pending solicitudes only
        $days_pending = SolicitudVacacion::where('cedula', $empleado->cedula)
            ->where('estado', 'pendiente')
            ->sum('dias_habiles');

        $days_available = max(0, $accrued_days - $days_taken - $days_pending);

        // Block requests if before 1 year or no available days
        if (! $has_one_year) {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'solicitar'])
                ->with('status', __('Aún no cumple un año en la empresa; no puede solicitar vacaciones.'));
        }

        if ($days_available <= 0) {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'solicitar'])
                ->with('status', __('No tiene días disponibles para solicitar en este momento.'));
        }

        // Validate start date minimum (only if a minimum start date is set)
        $fechaInicio = Carbon::parse($validated['fecha_inicio'])->startOfDay();
        if (isset($min_start_date) && $min_start_date && $fechaInicio->lt($min_start_date)) {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'solicitar'])
                ->with('status', __('La fecha de inicio debe ser como mínimo :date', ['date' => $min_start_date->format('Y-m-d')]));
        }

        // All good - create solicitud
        $validated['cedula'] = $empleado->cedula;
        $validated['nombre'] = Auth::user()->name;
        $validated['estado'] = 'pendiente';

        SolicitudVacacion::create($validated);

        return redirect()
            ->route('admin.vacaciones.index', ['tab' => 'consultas'])
            ->with('status', __('La solicitud se registró correctamente.'));
    }

    public function edit(string $id)
    {
        $solicitud = SolicitudVacacion::findOrFail($id);

        return view('admin.vacations.edit', compact('solicitud'));
    }

    public function update(Request $request, string $id)
    {
        $solicitud = SolicitudVacacion::findOrFail($id);

        $validated = $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'dias_habiles' => ['required', 'integer', 'min:0'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $solicitud->update($validated);

        return redirect()
            ->route('admin.vacaciones.index', ['tab' => 'consultas'])
            ->with('status', __('La solicitud se actualizó correctamente.'));
    }

    public function destroy(string $id)
    {
        $solicitud = SolicitudVacacion::findOrFail($id);
        $solicitud->delete();

        return redirect()
            ->route('admin.vacaciones.index', ['tab' => 'pendientes'])
            ->with('status', __('La solicitud fue cancelada.'));
    }

    public function approve(string $id)
    {
        $solicitud = SolicitudVacacion::findOrFail($id);
        $user = auth()->user();

        // Solo se pueden aprobar solicitudes pendientes
        if ($solicitud->estado !== 'pendiente') {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'pendientes'])
                ->with('status', __('Esta solicitud ya fue procesada.'));
        }

        // Si es supervisor y la solicitud es mayor a 2 días, no puede aprobar
        if ($user->hasRole('supervisor') && !$user->hasAnyRole(['rrhh', 'admin']) && $solicitud->dias_habiles > 2) {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Sin autorización',
                'text' => 'Las solicitudes mayores a 2 días deben ser aprobadas por Recursos Humanos.',
            ]);
            return redirect()->route('admin.solicitudes-pendientes.index');
        }

        // Buscar el empleado por cédula
        $empleado = Empleado::where('cedula', $solicitud->cedula)->first();

        if (!$empleado || !$empleado->user_id) {
            return redirect()
                ->route('admin.vacaciones.index', ['tab' => 'pendientes'])
                ->with('status', __('No se encontró el empleado asociado a esta solicitud.'));
        }

        // Actualizar estado de la solicitud a aprobado
        $solicitud->update(['estado' => 'aprobado']);

        // Crear registro en tabla vacaciones para descontar los días
        Vacacion::create([
            'usuario_id' => $empleado->user_id,
            'fecha_inicio' => $solicitud->fecha_inicio,
            'fecha_fin' => $solicitud->fecha_fin,
            'dias_habiles' => $solicitud->dias_habiles,
            'dias_calendario' => Carbon::parse($solicitud->fecha_inicio)
                ->diffInDays(Carbon::parse($solicitud->fecha_fin)) + 1,
            'estado' => 'aprobado',
            'observaciones' => $solicitud->observaciones,
        ]);

        return redirect()
            ->route('admin.solicitudes-pendientes.index')
            ->with('status', __('La solicitud fue aprobada correctamente.'));
    }

    public function reject(string $id)
    {
        $solicitud = SolicitudVacacion::findOrFail($id);
        $user = auth()->user();

        // Solo se pueden rechazar solicitudes pendientes
        if ($solicitud->estado !== 'pendiente') {
            return redirect()
                ->route('admin.solicitudes-pendientes.index')
                ->with('status', __('Esta solicitud ya fue procesada.'));
        }

        // Si es supervisor y la solicitud es mayor a 2 días, no puede rechazar
        if ($user->hasRole('supervisor') && !$user->hasAnyRole(['rrhh', 'admin']) && $solicitud->dias_habiles > 2) {
            session()->flash('swal', [
                'icon' => 'warning',
                'title' => 'Sin autorización',
                'text' => 'Las solicitudes mayores a 2 días deben ser procesadas por Recursos Humanos.',
            ]);
            return redirect()->route('admin.solicitudes-pendientes.index');
        }

        // Actualizar estado de la solicitud a rechazado
        $solicitud->update(['estado' => 'rechazado']);

        return redirect()
            ->route('admin.solicitudes-pendientes.index')
            ->with('status', __('La solicitud fue rechazada.'));
    }
}
