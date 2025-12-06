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
      Schema::create('treino_exercicios', function (Blueprint $table) {
         $table->id();
         $table->foreignId('treino_id')->constrained('treinos')->onDelete('cascade');
         $table->integer('ordem')->default(0);
         $table->string('nome');
         $table->string('series')->nullable();
         $table->string('repeticoes')->nullable();
         $table->string('carga')->nullable();
         $table->string('descanso')->nullable();
         $table->string('observacao')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('treino_exercicios');
   }
};
