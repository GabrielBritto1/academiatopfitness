<?php

namespace App\Services;

use App\Mail\PaymentReminderEmail;
use App\Models\FinancialTransaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Mail;

class PaymentReminderService
{
    public function sendDueSoonReminders(?CarbonInterface $referenceDate = null): array
    {
        $referenceDate = $referenceDate
            ? Carbon::instance($referenceDate)->startOfDay()
            : now()->startOfDay();

        $students = User::query()
            ->role('aluno')
            ->where('status', true)
            ->whereNotNull('email')
            ->with(['aluno.unidade'])
            ->get();

        $processed = 0;
        $sent = 0;
        $skipped = 0;

        foreach ($students as $student) {
            $processed++;

            $aluno = $student->aluno;

            if (!$aluno) {
                $skipped++;
                continue;
            }

            $nextPendingCharge = FinancialTransaction::query()
                ->where('kind', 'conta_receber')
                ->where('user_id', $student->id)
                ->where('status', 'pendente')
                ->whereNotNull('due_date')
                ->orderBy('due_date')
                ->first();

            if (!$nextPendingCharge) {
                $skipped++;
                continue;
            }

            $dueDate = $nextPendingCharge->due_date->copy()->startOfDay();

            if (!$dueDate->isSameDay($referenceDate->copy()->addDays(7))) {
                $skipped++;
                continue;
            }

            if ($aluno->last_payment_reminder_sent_for?->isSameDay($dueDate)) {
                $skipped++;
                continue;
            }

            Mail::to($student->email)->send(new PaymentReminderEmail(
                studentName: $student->name,
                dueDate: $dueDate->translatedFormat('d \\d\\e F \\d\\e Y'),
                unitName: $aluno->unidade?->nome,
            ));

            $aluno->forceFill([
                'last_payment_reminder_sent_for' => $dueDate->toDateString(),
            ])->save();

            $sent++;
        }

        return [
            'processed' => $processed,
            'sent' => $sent,
            'skipped' => $skipped,
            'reference_date' => $referenceDate->toDateString(),
        ];
    }
}
