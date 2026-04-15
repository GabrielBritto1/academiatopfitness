<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'antigo@example.com',
        ]);

        $response = $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Nome Novo',
                'email' => 'novo@example.com',
            ]);

        $response->assertRedirect(route('profile.edit'));

        $user->refresh();

        $this->assertSame('Nome Novo', $user->name);
        $this->assertSame('novo@example.com', $user->email);
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('senha-antiga'),
        ]);

        $response = $this->actingAs($user)
            ->put(route('profile.password.update'), [
                'current_password' => 'senha-antiga',
                'password' => 'nova-senha-segura',
                'password_confirmation' => 'nova-senha-segura',
            ]);

        $response->assertRedirect(route('profile.password.edit'));

        $user->refresh();

        $this->assertTrue(Hash::check('nova-senha-segura', $user->password));
    }

    public function test_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('senha-antiga'),
        ]);

        $this->actingAs($user)
            ->from(route('profile.password.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'senha-errada',
                'password' => 'nova-senha-segura',
                'password_confirmation' => 'nova-senha-segura',
            ])
            ->assertRedirect(route('profile.password.edit'))
            ->assertSessionHasErrors('current_password');
    }
}
