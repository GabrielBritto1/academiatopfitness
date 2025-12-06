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
      Schema::create('treinos', function (Blueprint $table) {
         $table->id();
         $table->foreignId('planilha_id')->constrained('planilha_treinos');
         $table->string('sigla');
         $table->string('dias_semana')->nullable();
         $table->string('nome')->nullable();
         $table->text('observacoes')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('treinos');
   }
};
