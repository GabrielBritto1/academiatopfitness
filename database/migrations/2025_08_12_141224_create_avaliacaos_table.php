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
         $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade');
         $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');
         $table->string('peso')->nullable();
         $table->string('altura')->nullable();
         $table->string('imc')->nullable();
         $table->string('gordura')->nullable();
         $table->string('massa_muscular')->nullable();
         $table->string('circunferencia_cintura')->nullable();
         $table->string('circunferencia_quadril')->nullable();
         $table->string('circunferencia_braco_relaxado')->nullable();
         $table->string('circunferencia_braco_contraido')->nullable();
         $table->string('circunferencia_peito')->nullable();
         $table->string('circunferencia_coxa')->nullable();
         $table->string('circunferencia_panturrilha')->nullable();
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
