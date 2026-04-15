<?php

namespace Tests\Feature;

use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialCategoryWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_creation_can_return_to_transaction_screen(): void
    {
        $user = User::factory()->create();
        $redirectTo = route('financeiro.transacoes.create', ['kind' => 'conta_receber']);

        $response = $this->actingAs($user)
            ->post(route('financeiro.categorias.store'), [
                'name' => 'Mensalidades',
                'type' => 'receita',
                'is_active' => '1',
                'redirect_to' => $redirectTo,
            ]);

        $response->assertRedirect($redirectTo);

        $this->assertDatabaseHas('financial_categories', [
            'name' => 'Mensalidades',
            'type' => 'receita',
            'is_active' => 1,
        ]);
    }

    public function test_category_creation_accepts_checkbox_style_boolean_payload(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('financeiro.categorias.store'), [
                'name' => 'Serviços',
                'type' => 'receita',
                'is_active' => 'on',
            ]);

        $response->assertRedirect(route('financeiro.categorias.index', ['type' => 'receita']));

        $this->assertDatabaseHas('financial_categories', [
            'name' => 'Serviços',
            'type' => 'receita',
            'is_active' => 1,
        ]);
    }

    public function test_category_creation_can_be_saved_as_inactive(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('financeiro.categorias.store'), [
                'name' => 'Arquivo Morto',
                'type' => 'despesa',
                'is_active' => '0',
            ]);

        $response->assertRedirect(route('financeiro.categorias.index', ['type' => 'despesa']));

        $this->assertDatabaseHas('financial_categories', [
            'name' => 'Arquivo Morto',
            'type' => 'despesa',
            'is_active' => 0,
        ]);
    }

    public function test_used_category_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $category = FinancialCategory::create([
            'name' => 'Caixa Geral',
            'type' => 'receita',
            'is_active' => true,
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'financial_category_id' => $category->id,
            'description' => 'Receita teste',
            'amount' => 100,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($user)
            ->delete(route('financeiro.categorias.destroy', $category->id));

        $response->assertRedirect(route('financeiro.categorias.index', ['type' => 'receita']));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('financial_categories', [
            'id' => $category->id,
        ]);
    }
}
