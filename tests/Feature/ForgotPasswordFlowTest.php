<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'aluno@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'aluno@example.com',
            'password' => Hash::make('senha-antiga'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NovaSenha@123',
            'password_confirmation' => 'NovaSenha@123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('NovaSenha@123', $user->fresh()->password));
    }
}
