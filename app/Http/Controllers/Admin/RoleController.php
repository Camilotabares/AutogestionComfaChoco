<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return view ('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);
        
        $role = Role::create(['name' => $data['name']]);
        
        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Rol creado exitosamente',
        ]);
        
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view ('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {   
        $permissions = Permission::all();
        return view ('admin.roles.edit', compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        } else {
            $role->permissions()->detach();
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Rol actualizado exitosamente',
        ]);

        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Rol eliminado exitosamente',
        ]);

        return redirect()->route('admin.roles.index');
    }
}
