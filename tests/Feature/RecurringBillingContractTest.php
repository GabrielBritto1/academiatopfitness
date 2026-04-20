<?php

namespace Tests\Feature;

use App\Models\AcademiaUnidade;
use App\Models\Aluno;
use App\Models\AlunoPlanoUnidade;
use App\Models\FinancialTransaction;
use App\Models\Permission;
use App\Models\Planos;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
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

    public function test_student_show_displays_confirm_payment_button_for_open_plan_charge(): void
    {
        Carbon::setTestNow('2026-05-10 08:00:00');

        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Norte',
            'endereco' => 'Rua das Flores, 10',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Fit',
            'preco' => 99.90,
            'color' => '#333333',
        ]);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 99.90,
            'valor_total' => 99.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-15',
        ]);

        $transaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Fit',
            'due_date' => '2026-05-15',
            'amount' => 99.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'pix',
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($manager)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Confirmar Pagamento');
        $response->assertSee(route('financeiro.transacoes.marcar-pago', $transaction->id), false);

        Carbon::setTestNow();
    }

    public function test_student_show_hides_confirm_payment_button_when_due_date_is_more_than_seven_days_away(): void
    {
        Carbon::setTestNow('2026-05-10 08:00:00');

        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Oeste',
            'endereco' => 'Rua do Sol, 50',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Plus',
            'preco' => 109.90,
            'color' => '#555555',
        ]);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 109.90,
            'valor_total' => 109.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-25',
        ]);

        $transaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Plus',
            'due_date' => '2026-05-25',
            'amount' => 109.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'pix',
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($manager)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertDontSee('Confirmar Pagamento');
        $response->assertDontSee(route('financeiro.transacoes.marcar-pago', $transaction->id), false);

        Carbon::setTestNow();
    }

    public function test_student_show_displays_legacy_open_charge_without_contract_link_for_single_plan(): void
    {
        Carbon::setTestNow('2026-04-20 08:00:00');

        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua A, 100',
        ]);
        $plan = Planos::create([
            'name' => 'Mensalidade',
            'preco' => 110.00,
            'color' => '#666666',
        ]);

        AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 110.00,
            'valor_total' => 110.00,
            'valor_desconto' => 0,
            'forma_pagamento' => 'cartao',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-04-15',
        ]);

        $legacyTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => null,
            'description' => 'Mensalidade',
            'due_date' => '2026-04-15',
            'amount' => 110.00,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'cartao',
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($manager)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Confirmar Pagamento');
        $response->assertSee('Cobrança: 15/04/2026', false);
        $response->assertSee(route('financeiro.transacoes.marcar-pago', $legacyTransaction->id), false);

        Carbon::setTestNow();
    }

    public function test_confirming_payment_from_student_show_marks_transaction_as_paid(): void
    {
        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Leste',
            'endereco' => 'Av. Central, 500',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Start',
            'preco' => 89.90,
            'color' => '#444444',
        ]);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 89.90,
            'valor_total' => 89.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'cartao',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-18',
        ]);

        $transaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Start',
            'due_date' => '2026-05-18',
            'amount' => 89.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'cartao',
            'status' => 'pendente',
        ]);

        $this->actingAs($manager)
            ->from(route('aluno.show', $student->id))
            ->post(route('financeiro.transacoes.marcar-pago', $transaction->id), [])
            ->assertRedirect(route('aluno.show', $student->id));

        $this->assertSame('pago', $transaction->fresh()->status);
    }

    public function test_student_show_displays_edit_plan_link(): void
    {
        $manager = User::factory()->create();
        $student = $this->createStudent();
        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Praia',
            'endereco' => 'Av. Atlântica, 10',
        ]);
        $plan = Planos::create([
            'name' => 'Plano Light',
            'preco' => 79.90,
            'color' => '#888888',
        ]);

        $unit->planos()->attach($plan->id);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $plan->id,
            'valor_inicial' => 79.90,
            'valor_total' => 79.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-10',
        ]);

        $response = $this->actingAs($manager)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Editar Plano');
        $response->assertSee(route('aluno.planos.edit', [$student->id, $contract->id]), false);
    }

    public function test_manager_can_edit_student_plan_contract_and_sync_open_charge(): void
    {
        Permission::findOrCreate('students.manage', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');
        $student = $this->createStudent();
        $oldUnit = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua 1, 10',
        ]);
        $newUnit = AcademiaUnidade::create([
            'nome' => 'Unidade Leste',
            'endereco' => 'Rua 2, 20',
        ]);
        $oldPlan = Planos::create([
            'name' => 'Plano Base',
            'preco' => 90,
            'color' => '#111111',
        ]);
        $newPlan = Planos::create([
            'name' => 'Plano Performance',
            'preco' => 150,
            'color' => '#222222',
        ]);

        $oldUnit->planos()->attach($oldPlan->id);
        $newUnit->planos()->attach($newPlan->id);

        $contract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $oldUnit->id,
            'plano_id' => $oldPlan->id,
            'valor_inicial' => 90,
            'valor_total' => 90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
            'periodicidade' => 'mensal',
            'data_vencimento' => '2026-05-20',
        ]);

        $paidTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $oldUnit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Base',
            'due_date' => '2026-04-20',
            'paid_at' => '2026-04-20',
            'amount' => 90,
            'discount' => 0,
            'addition' => 0,
            'amount_paid' => 90,
            'payment_method' => 'pix',
            'status' => 'pago',
        ]);

        $openTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $oldUnit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $contract->id,
            'description' => 'Mensalidade - Plano Base',
            'due_date' => '2026-05-20',
            'amount' => 90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'pix',
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($manager)
            ->from(route('aluno.planos.edit', [$student->id, $contract->id]))
            ->put(route('aluno.planos.update', [$student->id, $contract->id]), [
                'academia_unidade_id' => $newUnit->id,
                'plano_id' => $newPlan->id,
                'valor_inicial' => 150,
                'valor_desconto' => 10,
                'forma_pagamento' => 'cartao',
                'periodicidade' => 'anual',
                'data_vencimento' => '2026-06-15',
            ]);

        $response->assertRedirect(route('aluno.show', $student->id) . '#planos');

        $this->assertSame($newUnit->id, $contract->fresh()->academia_unidade_id);
        $this->assertSame($newPlan->id, $contract->fresh()->plano_id);
        $this->assertSame('cartao', $contract->fresh()->forma_pagamento);
        $this->assertSame('anual', $contract->fresh()->periodicidade);
        $this->assertSame('2026-06-15', $contract->fresh()->data_vencimento->toDateString());
        $this->assertSame('150.00', $contract->fresh()->valor_inicial);
        $this->assertSame('135.00', $contract->fresh()->valor_total);
        $this->assertSame('10.00', $contract->fresh()->valor_desconto);

        $this->assertSame($oldUnit->id, $paidTransaction->fresh()->academia_unidade_id);
        $this->assertSame('Mensalidade - Plano Base', $paidTransaction->fresh()->description);
        $this->assertSame('pix', $paidTransaction->fresh()->payment_method);

        $this->assertSame($newUnit->id, $openTransaction->fresh()->academia_unidade_id);
        $this->assertSame('Anuidade - Plano Performance', $openTransaction->fresh()->description);
        $this->assertSame('2026-06-15', $openTransaction->fresh()->due_date->toDateString());
        $this->assertSame('150.00', number_format((float) $openTransaction->fresh()->amount, 2, '.', ''));
        $this->assertSame('15.00', number_format((float) $openTransaction->fresh()->discount, 2, '.', ''));
        $this->assertSame('cartao', $openTransaction->fresh()->payment_method);
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
