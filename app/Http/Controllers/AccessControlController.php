<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class AccessControlController extends Controller
{
    public function __construct(private PermissionRegistrar $permissionRegistrar)
    {
    }

    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        $permissionGroups = config('access-control.groups', []);

        return view('access-control.index', compact('roles', 'permissions', 'permissionGroups'));
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::findOrCreate($validated['name'], config('auth.defaults.guard', 'web'));
        $this->permissionRegistrar->forgetCachedPermissions();

        return redirect()->route('access-control.index')
            ->with('success', 'Role criada com sucesso!');
    }

    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::findOrCreate($validated['name'], config('auth.defaults.guard', 'web'));
        $this->permissionRegistrar->forgetCachedPermissions();

        return redirect()->route('access-control.index')
            ->with('success', 'Permissão criada com sucesso!');
    }

    public function syncRolePermissions(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])->get();
        $role->syncPermissions($permissions);
        $this->permissionRegistrar->forgetCachedPermissions();

        return redirect()->route('access-control.index')
            ->with('success', 'Permissões da role atualizadas com sucesso!');
    }
}
