<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_professors_manage_permission_can_create_professor(): void
    {
        Permission::findOrCreate('professors.manage', 'web');
        Role::findOrCreate('professor', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('professors.manage');

        $response = $this->actingAs($manager)
            ->post(route('professor.store'), [
                'name' => 'Professor Teste',
                'email' => 'professor@example.com',
            ]);

        $response->assertRedirect(route('professor.index'));

        $professor = User::where('email', 'professor@example.com')->firstOrFail();

        $this->assertTrue($professor->hasRole('professor'));
    }

    public function test_user_without_professors_manage_permission_cannot_create_professor(): void
    {
        Role::findOrCreate('professor', 'web');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('professor.store'), [
                'name' => 'Professor Teste',
                'email' => 'professor@example.com',
            ])
            ->assertForbidden();
    }
}
