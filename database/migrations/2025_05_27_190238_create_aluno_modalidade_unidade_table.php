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
      Schema::create('aluno_modalidade_unidade', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
         $table->foreignId('academia_unidade_id')->constrained('academia_unidades')->onDelete('cascade');
         $table->foreignId('modalidade_id')->constrained('modalidades')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('aluno_modalidade_unidade');
   }
};
