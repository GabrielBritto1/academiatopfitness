<?php

namespace Tests\Feature;

use App\Mail\PaymentReminderEmail;
use App\Models\Aluno;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentReminderCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_payment_reminder_seven_days_before_due_date(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-04-10 08:00:00');

        $student = $this->createAlunoUser('alice@example.com', 'Alice', '2026-03-17');
        $this->createAlunoUser('bruno@example.com', 'Bruno', '2026-03-18');

        $this->artisan('topfitness:send-payment-reminders')
            ->expectsOutputToContain('1 email(s) enviado(s)')
            ->assertSuccessful();

        Mail::assertSent(PaymentReminderEmail::class, function (PaymentReminderEmail $mail) use ($student) {
            return $mail->hasTo($student->email)
                && $mail->studentName === 'Alice';
        });

        Mail::assertNotSent(PaymentReminderEmail::class, function (PaymentReminderEmail $mail) {
            return $mail->hasTo('bruno@example.com');
        });

        $this->assertSame(
            '2026-04-17',
            $student->fresh()->aluno->last_payment_reminder_sent_for->toDateString()
        );
    }

    public function test_command_does_not_send_duplicate_reminder_for_same_due_date(): void
    {
        Mail::fake();
        Carbon::setTestNow('2026-04-10 08:00:00');

        $student = $this->createAlunoUser(
            'carla@example.com',
            'Carla',
            '2026-03-17',
            '2026-04-17'
        );

        $this->artisan('topfitness:send-payment-reminders')->assertSuccessful();

        Mail::assertNothingSent();
        $this->assertSame(
            '2026-04-17',
            $student->fresh()->aluno->last_payment_reminder_sent_for->toDateString()
        );
    }

    private function createAlunoUser(
        string $email,
        string $name,
        string $registeredAt,
        ?string $lastReminderSentFor = null
    ): User {
        $role = Role::firstOrCreate(['name' => 'aluno']);

        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'status' => true,
        ]);

        $user->roles()->attach($role->id);

        Aluno::create([
            'user_id' => $user->id,
            'registered_at' => $registeredAt,
            'last_payment_reminder_sent_for' => $lastReminderSentFor,
        ]);

        return $user;
    }
}
