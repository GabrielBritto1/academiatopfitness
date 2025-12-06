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
        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->boolean('is_padrao')->default(false)->after('id');
            $table->string('nome')->nullable()->after('is_padrao');
        });

        // Tornar aluno_id nullable para permitir planilhas padrão
        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->dropForeign(['aluno_id']);
        });

        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->unsignedBigInteger('aluno_id')->nullable()->change();
        });

        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->foreign('aluno_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->dropColumn(['is_padrao', 'nome']);
        });

        // Reverter aluno_id para not null (pode causar problemas se houver planilhas padrão)
        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->dropForeign(['aluno_id']);
        });

        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->unsignedBigInteger('aluno_id')->nullable(false)->change();
        });

        Schema::table('planilha_treinos', function (Blueprint $table) {
            $table->foreign('aluno_id')->references('id')->on('users');
        });
    }
};
