<?php

use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $guardName = config('auth.defaults.guard', 'web');

        if (Schema::hasTable('roles')) {
            DB::table('roles')->whereNull('guard_name')->update(['guard_name' => $guardName]);
            DB::table('roles')->where('guard_name', '')->update(['guard_name' => $guardName]);
        }

        if (Schema::hasTable('abilities')) {
            $abilities = DB::table('abilities')->select('name')->get();

            foreach ($abilities as $ability) {
                Permission::findOrCreate($ability->name, $guardName);
            }
        }

        foreach (config('access-control.permissions', []) as $permissionName => $label) {
            Permission::findOrCreate($permissionName, $guardName);
        }

        foreach (array_keys(config('access-control.default_roles', [])) as $roleName) {
            Role::findOrCreate($roleName, $guardName);
        }

        if (Schema::hasTable('ability_role') && Schema::hasTable('abilities')) {
            $legacyRolePermissions = DB::table('ability_role')
                ->join('abilities', 'abilities.id', '=', 'ability_role.ability_id')
                ->select('ability_role.role_id', 'abilities.name as permission_name')
                ->get();

            foreach ($legacyRolePermissions as $legacyRolePermission) {
                $role = Role::find($legacyRolePermission->role_id);
                $permission = Permission::findByName($legacyRolePermission->permission_name, $guardName);

                if ($role && $permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }

        if (Schema::hasTable('role_user')) {
            $modelType = \App\Models\User::class;
            $legacyAssignments = DB::table('role_user')
                ->select('role_id', 'user_id')
                ->get();

            foreach ($legacyAssignments as $legacyAssignment) {
                DB::table('model_has_roles')->updateOrInsert([
                    'role_id' => $legacyAssignment->role_id,
                    'model_type' => $modelType,
                    'model_id' => $legacyAssignment->user_id,
                ], []);
            }
        }

        foreach (config('access-control.default_roles', []) as $roleName => $permissions) {
            $role = Role::findByName($roleName, $guardName);

            if ($permissions !== []) {
                $role->givePermissionTo($permissions);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
