<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException as SpatieUnauthorizedException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then:function(){
            Route::middleware('web' ,'auth')
            ->prefix('admin')
            ->name('admin.')
            ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirigir a admin.dashboard cuando no hay permisos (403)
        $exceptions->render(function (AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permisos para acceder a esta página.'
                ], 403);
            }
            return redirect()->route('admin.dashboard')->with('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permisos para acceder a esa sección.'
            ]);
        });

        $exceptions->render(function (SpatieUnauthorizedException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permisos para acceder a esta página.'
                ], 403);
            }
            return redirect()->route('admin.dashboard')->with('swal', [
                'icon' => 'error',
                'title' => 'Acceso denegado',
                'text' => 'No tienes permisos para acceder a esa sección.'
            ]);
        });
    })->create();
