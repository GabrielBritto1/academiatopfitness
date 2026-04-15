<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_roles_manage_permission_can_access_access_control_screen(): void
    {
        Permission::findOrCreate('roles.manage', 'web');
        $user = User::factory()->create();
        $user->givePermissionTo('roles.manage');

        $response = $this->actingAs($user)->get(route('access-control.index'));

        $response->assertOk();
        $response->assertSee('Acessos e Permissões');
    }

    public function test_user_without_roles_manage_permission_cannot_access_access_control_screen(): void
    {
        Permission::findOrCreate('roles.manage', 'web');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('access-control.index'));

        $response->assertForbidden();
    }

    public function test_admin_role_is_super_admin_via_gate_before(): void
    {
        $role = Role::findOrCreate('admin', 'web');
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('access-control.index'));

        $response->assertOk();
    }

    public function test_syncing_role_permissions_revokes_access_on_next_request(): void
    {
        $permission = Permission::findOrCreate('roles.manage', 'web');
        $role = Role::findOrCreate('gerente', 'web');
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $this->actingAs($user)
            ->put(route('access-control.roles.permissions.sync', $role->id), [
                'permissions' => [],
            ])
            ->assertRedirect(route('access-control.index'));

        $this->actingAs($user)
            ->get(route('access-control.index'))
            ->assertForbidden();
    }
}
