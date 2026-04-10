<?php

namespace App\Services;

use App\Mail\PaymentReminderEmail;
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
            ->where('status', true)
            ->whereNotNull('email')
            ->whereHas('roles', fn($query) => $query->where('name', 'aluno'))
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

            if (!$aluno->shouldReceivePaymentReminderOn($referenceDate)) {
                $skipped++;
                continue;
            }

            $dueDate = $aluno->nextBillingDate($referenceDate);

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
