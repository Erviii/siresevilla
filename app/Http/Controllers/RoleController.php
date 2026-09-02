<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Listado de Roles y sus Permisos
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all(); // <-- Agregar esta línea
        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Formulario para crear un nuevo Rol
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Guardar el Rol y sus Permisos asignados
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => trim($request->name)]);

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado correctamente con sus permisos.');
    }

    /**
     * Formulario para editar un Rol y su Matriz de Permisos
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar Permisos del Rol
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => trim($request->name)]);

            // Sincronizar Permisos seleccionados en los checkboxes
            $role->syncPermissions($request->permissions ?? []);
        });

        return redirect()->route('roles.index')
            ->with('success', 'Matriz de permisos del rol actualizada correctamente.');
    }
}