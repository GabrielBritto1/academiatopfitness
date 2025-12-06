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
      Schema::create('avaliacaos', function (Blueprint $table) {
         $table->id();

         // RELACIONAMENTOS
         $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade');
         $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');

         // ======================
         // MEDIDAS BÁSICAS
         // ======================
         $table->decimal('peso', 5, 2)->nullable();
         $table->decimal('altura', 5, 2)->nullable();
         $table->decimal('imc', 5, 2)->nullable();
         $table->decimal('massa_muscular', 5, 2)->nullable();

         // ======================
         // PERIMETRIAS PROFISSIONAIS
         // ======================
         $table->decimal('torax', 5, 2)->nullable();
         $table->decimal('cintura', 5, 2)->nullable();
         $table->decimal('abdomen_medida', 5, 2)->nullable();
         $table->decimal('quadril', 5, 2)->nullable();

         $table->decimal('braco_relaxado_esquerdo', 5, 2)->nullable();
         $table->decimal('braco_relaxado_direito', 5, 2)->nullable();

         $table->decimal('braco_contraido_esquerdo', 5, 2)->nullable();
         $table->decimal('braco_contraido_direito', 5, 2)->nullable();

         $table->decimal('coxa_medial', 5, 2)->nullable();
         $table->decimal('panturrilha', 5, 2)->nullable();

         // ======================
         // DOBRAS CUTÂNEAS — POLLOCK 3/7
         // ======================
         $table->decimal('peito', 5, 2)->nullable();
         $table->decimal('triceps', 5, 2)->nullable();
         $table->decimal('subescapular', 5, 2)->nullable();
         $table->decimal('axilar_media', 5, 2)->nullable();
         $table->decimal('supra_iliaca', 5, 2)->nullable();
         $table->decimal('abdomen_dobra', 5, 2)->nullable(); // dobra ≠ medida abdominal
         $table->decimal('coxa_dobra', 5, 2)->nullable();     // dobra

         // Protocolo e sexo
         $table->string('protocolo')->nullable();         // pollock3 / pollock7
         $table->string('sexo_avaliacao')->nullable();    // masculino / feminino

         // ======================
         // CÁLCULOS AUTOMÁTICOS
         // ======================
         $table->decimal('soma_dobras', 6, 2)->nullable();
         $table->decimal('densidade', 8, 5)->nullable();
         $table->decimal('gordura', 5, 2)->nullable(); // %

         // ======================
         // OBSERVAÇÕES
         // ======================
         $table->text('observacoes')->nullable();

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('avaliacaos');
   }
};
