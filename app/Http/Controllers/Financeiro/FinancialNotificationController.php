<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;

class FinancialNotificationController extends Controller
{
   public function dueSoon(): JsonResponse
   {
      $referenceDate = now()->startOfDay();
      $startDate = $referenceDate->copy()->subDays(7);
      $endDate = $referenceDate->copy()->addDays(7);

      $billingNotifications = FinancialTransaction::query()
         ->where('kind', 'conta_receber')
         ->where('status', 'pendente')
         ->whereNotNull('due_date')
         ->whereDate('due_date', '>=', $startDate->toDateString())
         ->whereDate('due_date', '<=', $endDate->toDateString())
         ->with(['user.aluno', 'category'])
         ->orderBy('due_date')
         ->limit(10)
         ->get()
         ->map(function (FinancialTransaction $transaction) use ($referenceDate) {
            $dueDate = $transaction->due_date->copy()->startOfDay();
            $daysUntilDue = $referenceDate->diffInDays($dueDate, false);
            $studentName = $transaction->user?->name ?? 'Aluno sem nome';
            $isOverdue = $daysUntilDue < 0;

            return [
               'kind' => 'billing',
               'sort_priority' => $isOverdue ? 0 : 2,
               'sort_date' => $dueDate->timestamp,
               'message' => $this->makeMessage($studentName, $daysUntilDue),
               'date_label' => $this->makeDateLabel($dueDate, $daysUntilDue),
               'tone_class' => $isOverdue ? 'text-danger' : 'text-muted',
               'secondary_text' => $transaction->description,
               'url' => route('financeiro.transacoes.edit', $transaction->id),
               'icon' => $isOverdue
                  ? 'fas fa-exclamation-triangle text-danger'
                  : ($daysUntilDue <= 1 ? 'fas fa-exclamation-circle text-warning' : 'fas fa-calendar-alt text-info'),
               'is_overdue' => $isOverdue,
            ];
         });

      $birthdayNotifications = Aluno::query()
         ->with('user')
         ->whereNotNull('data_nascimento')
         ->get()
         ->filter(fn (Aluno $aluno) => $aluno->isBirthdayToday($referenceDate))
         ->map(function (Aluno $aluno) use ($referenceDate) {
            $studentName = $aluno->user?->name ?? 'Aluno sem nome';

            return [
               'kind' => 'birthday',
               'sort_priority' => 1,
               'sort_date' => $referenceDate->timestamp,
               'message' => "Hoje é o aniversário de {$studentName} 🎂",
               'date_label' => 'Hoje',
               'tone_class' => 'text-success',
               'secondary_text' => 'Envie uma mensagem de parabéns.',
               'url' => route('aluno.show', $aluno->user_id) . '#birthday-greetings',
               'icon' => 'fas fa-birthday-cake text-success',
               'is_overdue' => false,
            ];
         });

      $notifications = $billingNotifications
         ->concat($birthdayNotifications)
         ->sortBy([
            ['sort_priority', 'asc'],
            ['sort_date', 'asc'],
         ])
         ->take(10)
         ->values();

      $hasOverdue = $notifications->contains(fn (array $notification) => $notification['kind'] === 'billing' && $notification['is_overdue']);
      $hasBirthday = $notifications->contains(fn (array $notification) => $notification['kind'] === 'birthday');

      return response()->json([
         'label' => $notifications->count(),
         'label_color' => $notifications->isEmpty() ? 'secondary' : ($hasOverdue ? 'danger' : ($hasBirthday ? 'success' : 'warning')),
         'icon_color' => $notifications->isEmpty() ? 'muted' : ($hasOverdue ? 'danger' : ($hasBirthday ? 'success' : 'warning')),
         'dropdown' => view('financeiro.notificacoes.dropdown', [
            'notifications' => $notifications,
         ])->render(),
      ]);
   }

   private function makeMessage(string $studentName, int $daysUntilDue): string
   {
      if ($daysUntilDue < 0) {
         return "Já vencido do aluno {$studentName}";
      }

      if ($daysUntilDue === 0) {
         return "Vence hoje para o aluno {$studentName}";
      }

      if ($daysUntilDue === 1) {
         return "1 dia para o vencimento do aluno {$studentName}";
      }

      return "{$daysUntilDue} dias para o vencimento do aluno {$studentName}";
   }

   private function makeDateLabel(\Carbon\Carbon $dueDate, int $daysUntilDue): string
   {
      if ($daysUntilDue < 0) {
         $daysOverdue = abs($daysUntilDue);

         return $daysOverdue === 1 ? '1 dia atrasado' : "{$daysOverdue} dias atrasado";
      }

      if ($daysUntilDue === 0) {
         return 'Hoje';
      }

      if ($daysUntilDue === 1) {
         return 'Amanhã';
      }

      return $dueDate->format('d/m');
   }
}
