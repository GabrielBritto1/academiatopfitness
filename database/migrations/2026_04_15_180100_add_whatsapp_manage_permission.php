<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        Permission::findOrCreate('whatsapp.manage', $guard);

        $adminRole = Role::findOrCreate('admin', $guard);
        $adminRole->givePermissionTo('whatsapp.manage');
    }

    public function down(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        $permission = Permission::where('name', 'whatsapp.manage')
            ->where('guard_name', $guard)
            ->first();

        if ($permission) {
            Role::where('guard_name', $guard)->get()->each(function (Role $role) use ($permission) {
                $role->revokePermissionTo($permission);
            });

            $permission->delete();
        }
    }
};
