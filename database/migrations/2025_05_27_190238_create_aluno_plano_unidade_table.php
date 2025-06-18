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
      Schema::create('aluno_plano_unidade', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
         $table->foreignId('academia_unidade_id')->constrained('academia_unidades')->onDelete('cascade');
         $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
         $table->float('valor_inicial')->default(0);
         $table->float('valor_total')->default(0);
         $table->float('valor_desconto')->default(0);
         $table->string('forma_pagamento')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('aluno_plano_unidade');
   }
};
