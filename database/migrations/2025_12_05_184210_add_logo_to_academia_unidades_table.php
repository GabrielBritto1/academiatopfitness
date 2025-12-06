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
        Schema::table('academia_unidades', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('endereco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academia_unidades', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
