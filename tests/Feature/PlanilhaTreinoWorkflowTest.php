<?php

namespace Tests\Feature;

use App\Models\AcademiaUnidade;
use App\Models\PlanilhaTreino;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanilhaTreinoWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_open_treino_create_for_standard_planilha_without_student(): void
    {
        $user = User::factory()->create();
        $professor = User::factory()->create();
        $unidade = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua A, 123',
        ]);

        $planilha = PlanilhaTreino::create([
            'is_padrao' => true,
            'nome' => 'Iniciante',
            'aluno_id' => null,
            'professor_id' => $professor->id,
            'unidade_id' => $unidade->id,
        ]);

        $response = $this->actingAs($user)->get(route('treino.create', [
            'planilha_id' => $planilha->id,
        ]));

        $response->assertOk();
        $response->assertSee('Padrão Iniciante');
    }

    public function test_custom_planilha_requires_student_on_backend(): void
    {
        $user = User::factory()->create();
        $professor = User::factory()->create();
        $unidade = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua A, 123',
        ]);

        $response = $this->actingAs($user)
            ->from(route('planilha-treino.create'))
            ->post(route('planilha-treino.store'), [
                'professor_id' => $professor->id,
                'unidade_id' => $unidade->id,
                'observacoes' => 'Teste',
            ]);

        $response->assertRedirect(route('planilha-treino.create'));
        $response->assertSessionHasErrors('aluno_id');
    }
}
