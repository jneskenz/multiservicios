<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:ver_roles')->only(['index']);
        $this->middleware('can:crear_roles')->only(['create', 'store']);
        $this->middleware('can:editar_roles')->only(['edit', 'update']);
        $this->middleware('can:eliminar_roles')->only(['destroy']);
    }

    public function index(Request $request)
    {

        $grupoActual = $request->input('grupoEmpresa');
        if (!$grupoActual) {
            abort(404, 'Grupo Empresarial no especificado.');
        }

        $roles = Role::all();

        return view('apps.workspace.roles.index', compact('roles', 'grupoActual'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('erp.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('erp.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', 'No puedes eliminar el rol de administrador');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente');
    }
}