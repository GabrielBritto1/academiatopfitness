<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\FinancialTransaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentActivationByPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_stays_inactive_while_payment_is_pending(): void
    {
        $manager = User::factory()->create();
        $student = $this->createInactiveStudent();

        $this->actingAs($manager)
            ->post(route('financeiro.transacoes.store'), [
                'kind' => 'conta_receber',
                'user_id' => $student->id,
                'description' => 'Mensalidade pendente',
                'amount' => 100,
                'discount' => 0,
                'addition' => 0,
                'status' => 'pendente',
            ])
            ->assertRedirect(route('financeiro.contas-receber.index'));

        $this->assertFalse($student->fresh()->status);
    }

    public function test_student_is_activated_when_payment_is_marked_as_paid(): void
    {
        $manager = User::factory()->create();
        $student = $this->createInactiveStudent();

        $transaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade',
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $this->actingAs($manager)
            ->post(route('financeiro.transacoes.marcar-pago', $transaction->id), [])
            ->assertRedirect();

        $this->assertTrue($student->fresh()->status);
        $this->assertSame('pago', $transaction->fresh()->status);
    }

    private function createInactiveStudent(): User
    {
        Role::findOrCreate('aluno', 'web');

        $user = User::factory()->create([
            'status' => false,
        ]);

        $user->assignRole('aluno');

        Aluno::create([
            'user_id' => $user->id,
            'registered_at' => now()->toDateString(),
        ]);

        return $user;
    }
}
