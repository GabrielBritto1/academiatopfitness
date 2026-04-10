<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->date('registered_at')->nullable()->after('user_id');
            $table->date('last_payment_reminder_sent_for')->nullable()->after('registered_at');
        });

        DB::table('alunos')
            ->select('id', 'user_id', 'created_at')
            ->orderBy('id')
            ->chunkById(100, function ($alunos) {
                $userCreatedAt = DB::table('users')
                    ->whereIn('id', $alunos->pluck('user_id')->filter()->all())
                    ->pluck('created_at', 'id');

                foreach ($alunos as $aluno) {
                    $fallbackDate = $aluno->created_at
                        ?? $userCreatedAt[$aluno->user_id] ?? now();

                    DB::table('alunos')
                        ->where('id', $aluno->id)
                        ->update([
                            'registered_at' => Carbon::parse($fallbackDate)->toDateString(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropColumn([
                'registered_at',
                'last_payment_reminder_sent_for',
            ]);
        });
    }
};
