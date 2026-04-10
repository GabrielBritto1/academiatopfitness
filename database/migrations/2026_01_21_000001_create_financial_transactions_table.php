<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('financial_transactions', function (Blueprint $table) {
         $table->id();

         // Tipo geral da transação: conta a receber (entrada) ou conta a pagar (saída)
         $table->enum('kind', ['conta_receber', 'conta_pagar']);

         // Vínculo com categoria (receita/despesa)
         $table->foreignId('financial_category_id')
            ->nullable()
            ->constrained('financial_categories')
            ->nullOnDelete();

         // Vínculo com unidade da academia (opcional)
         $table->foreignId('academia_unidade_id')
            ->nullable()
            ->constrained('academia_unidades')
            ->nullOnDelete();

         // Vínculo com usuário/aluno (opcional) - para mensalidades, matrículas etc.
         $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

         // Vínculo com contrato/plano do aluno (opcional)
         $table->foreignId('aluno_plano_unidade_id')
            ->nullable()
            ->constrained('aluno_plano_unidade')
            ->nullOnDelete();

         // Descrição livre do lançamento
         $table->string('description');

         // Datas
         $table->date('due_date')->nullable();      // vencimento
         $table->date('paid_at')->nullable();       // data efetiva de pagamento

         // Valores
         $table->decimal('amount', 10, 2);          // valor original
         $table->decimal('discount', 10, 2)->default(0); // descontos aplicados
         $table->decimal('addition', 10, 2)->default(0); // acréscimos, juros, multas
         $table->decimal('amount_paid', 10, 2)->nullable(); // valor efetivamente pago/recebido

         // Forma de pagamento (PIX, cartão, dinheiro, boleto, etc.)
         $table->string('payment_method')->nullable();

         // Status da transação
         $table->enum('status', ['pendente', 'pago', 'vencido', 'cancelado'])->default('pendente');

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('financial_transactions');
   }
};

