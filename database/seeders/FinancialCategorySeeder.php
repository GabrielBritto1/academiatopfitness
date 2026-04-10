<?php

namespace Database\Seeders;

use App\Models\FinancialCategory;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      // Categorias de Receita
      $receitas = [
         'Mensalidade',
         'Matrícula',
         'Produtos',
         'Serviços',
         'Outras Receitas',
      ];

      foreach ($receitas as $receita) {
         FinancialCategory::create([
            'name' => $receita,
            'type' => 'receita',
            'is_active' => true,
         ]);
      }

      // Categorias de Despesa
      $despesas = [
         'Aluguel',
         'Água',
         'Luz',
         'Internet',
         'Folha de Pagamento',
         'Manutenção',
         'Material de Limpeza',
         'Equipamentos',
         'Marketing',
         'Outras Despesas',
      ];

      foreach ($despesas as $despesa) {
         FinancialCategory::create([
            'name' => $despesa,
            'type' => 'despesa',
            'is_active' => true,
         ]);
      }
   }
}
