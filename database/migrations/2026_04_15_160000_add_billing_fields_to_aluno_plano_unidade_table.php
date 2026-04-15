<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aluno_plano_unidade', function (Blueprint $table) {
            if (!Schema::hasColumn('aluno_plano_unidade', 'periodicidade')) {
                $table->string('periodicidade')->default('mensal')->after('forma_pagamento');
            }

            if (!Schema::hasColumn('aluno_plano_unidade', 'data_vencimento')) {
                $table->date('data_vencimento')->nullable()->after('periodicidade');
            }
        });

        DB::table('aluno_plano_unidade')
            ->whereNull('periodicidade')
            ->update(['periodicidade' => 'mensal']);

        DB::statement("
            UPDATE aluno_plano_unidade
            SET data_vencimento = DATE(created_at)
            WHERE data_vencimento IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('aluno_plano_unidade', function (Blueprint $table) {
            if (Schema::hasColumn('aluno_plano_unidade', 'data_vencimento')) {
                $table->dropColumn('data_vencimento');
            }

            if (Schema::hasColumn('aluno_plano_unidade', 'periodicidade')) {
                $table->dropColumn('periodicidade');
            }
        });
    }
};
