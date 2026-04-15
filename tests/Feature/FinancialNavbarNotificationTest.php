<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialNavbarNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_due_soon_notifications_endpoint_lists_pending_student_charge(): void
    {
        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'João',
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade Abril',
            'due_date' => now()->addDay()->toDateString(),
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade distante',
            'due_date' => now()->addDays(10)->toDateString(),
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($viewer)
            ->getJson(route('financeiro.notificacoes.vencimentos'));

        $dropdown = $response->json('dropdown');

        $response->assertOk();
        $response->assertJsonPath('label', 1);
        $response->assertJsonPath('label_color', 'warning');
        $this->assertStringContainsString('1 dia para o vencimento do aluno João', $dropdown);
        $this->assertStringContainsString('Mensalidade Abril', $dropdown);
        $this->assertStringNotContainsString('Mensalidade distante', $dropdown);
    }

    public function test_due_soon_notifications_endpoint_returns_empty_state_when_there_are_no_matches(): void
    {
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)
            ->getJson(route('financeiro.notificacoes.vencimentos'));

        $dropdown = $response->json('dropdown');

        $response->assertOk();
        $response->assertJsonPath('label', 0);
        $response->assertJsonPath('label_color', 'secondary');
        $this->assertStringContainsString('Sem notificações', $dropdown);
    }

    public function test_due_soon_notifications_endpoint_lists_recently_overdue_student_charge(): void
    {
        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'João',
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade vencida',
            'due_date' => now()->subDays(3)->toDateString(),
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade antiga',
            'due_date' => now()->subDays(8)->toDateString(),
            'amount' => 120,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($viewer)
            ->getJson(route('financeiro.notificacoes.vencimentos'));

        $dropdown = $response->json('dropdown');

        $response->assertOk();
        $response->assertJsonPath('label', 1);
        $response->assertJsonPath('label_color', 'danger');
        $this->assertStringContainsString('Já vencido do aluno João', $dropdown);
        $this->assertStringContainsString('3 dias atrasado', $dropdown);
        $this->assertStringContainsString('Mensalidade vencida', $dropdown);
        $this->assertStringContainsString('fa-exclamation-triangle text-danger', $dropdown);
        $this->assertStringNotContainsString('Mensalidade antiga', $dropdown);
    }

    public function test_due_soon_notifications_endpoint_lists_student_birthday(): void
    {
        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'João',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '11999998888',
            'sexo' => 'M',
            'data_nascimento' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($viewer)
            ->getJson(route('financeiro.notificacoes.vencimentos'));

        $dropdown = $response->json('dropdown');

        $response->assertOk();
        $response->assertJsonPath('label', 1);
        $response->assertJsonPath('label_color', 'success');
        $this->assertStringContainsString('Hoje é o aniversário de João 🎂', $dropdown);
        $this->assertStringContainsString('Envie uma mensagem de parabéns.', $dropdown);
    }
}
