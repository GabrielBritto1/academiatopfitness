<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            if (! Schema::hasColumn('alunos', 'data_nascimento')) {
                $table->date('data_nascimento')->nullable()->after('sexo');
            }
        });

        Schema::table('alunos', function (Blueprint $table) {
            if (Schema::hasColumn('alunos', 'idade')) {
                $table->dropColumn('idade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            if (! Schema::hasColumn('alunos', 'idade')) {
                $table->integer('idade')->nullable()->after('sexo');
            }
        });

        Schema::table('alunos', function (Blueprint $table) {
            if (Schema::hasColumn('alunos', 'data_nascimento')) {
                $table->dropColumn('data_nascimento');
            }
        });
    }
};
