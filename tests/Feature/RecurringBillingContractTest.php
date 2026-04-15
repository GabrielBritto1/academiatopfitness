<?php

namespace Tests\Feature;

use App\Models\AcademiaUnidade;
use App\Models\Aluno;
use App\Models\AlunoPlanoUnidade;
use App\Models\FinancialTransaction;
use App\Models\Planos;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecurringBillingContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_plan_contract_stores_frequency_and_custom_due_date(): void
    {
        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua Principal, 100',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Premium',
            'preco' => 149.90,
            'color' => '#111111',
        ]);

        $response = $this->actingAs($manager)
            ->post(route('aluno.store2'), [
                'user_id' => $student->id,
                'unidades' => [$unit->id],
                'planos' => [$plan->id],
                'valores' => [149.90],
                'descontos' => [10],
                'periodicidades' => ['semestral'],
                'datas_vencimento' => ['2026-05-20'],
                'forma_pagamento' => 'pix',
            ]);

        $response->assertRedirect(route('aluno.index'));

        $contract = AlunoPlanoUnidade::firstOrFail();

        $this->assertSame($student->id, $contract->user_id);
        $this->assertSame('semestral', $contract->periodicidade);
        $this->assertSame('2026-05-20', $contract->data_vencimento->toDateString());

        $transaction = FinancialTransaction::where('aluno_plano_unidade_id', $contract->id)->firstOrFail();

        $this->assertSame('2026-05-20', $transaction->due_date->toDateString());
        $this->assertSame('Semestralidade - Plano Premium', $transaction->description);
    }

    public function test_paying_recurring_charge_generates_next_due_transaction(): void
    {
        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Sul',
            'endereco' => 'Av. Brasil, 200',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Gold',
            'preco' => 120,
            'color' => '#222222',
        ]);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 120,
            'valor_total' => 120,
            'valor_desconto' => 0,
            'forma_pagamento' => 'boleto',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-10',
        ]);

        $transaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Gold',
            'due_date' => '2026-05-10',
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'boleto',
            'status' => 'pendente',
        ]);

        $this->actingAs($manager)
            ->post(route('financeiro.transacoes.marcar-pago', $transaction->id), [])
            ->assertRedirect();

        $nextTransaction = FinancialTransaction::query()
            ->where('aluno_plano_unidade_id', $contract->id)
            ->where('status', 'pendente')
            ->whereDate('due_date', '2026-06-10')
            ->first();

        $this->assertNotNull($nextTransaction);
        $this->assertSame('Mensalidade - Plano Gold', $nextTransaction->description);
    }

    private function createStudent(): User
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
