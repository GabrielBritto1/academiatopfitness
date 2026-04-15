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
      Schema::create('alunos', function (Blueprint $table) {
         $table->id();

         // Ligando aluno ao usuário que faz login
         $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

         // Dados do aluno (dados “pessoais / físicos” que não devem ficar em users)
         $table->string('cpf')->nullable()->unique();
         $table->string('telefone')->nullable();
         $table->string('sexo')->nullable();
         $table->date('data_nascimento')->nullable();
         $table->text('observacoes')->nullable();
         $table->string('foto')->nullable();

         // Relacionamento com unidade
         $table->foreignId('unidade_id')->nullable()->constrained('academia_unidades');

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('alunos');
   }
};
