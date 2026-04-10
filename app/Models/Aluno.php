<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
   protected $fillable = [
      'user_id',
      'registered_at',
      'last_payment_reminder_sent_for',
      'cpf',
      'telefone',
      'sexo',
      'idade',
      'observacoes',
      'foto',
      'unidade_id',
   ];

   protected function casts(): array
   {
      return [
         'registered_at' => 'date',
         'last_payment_reminder_sent_for' => 'date',
         'created_at' => 'datetime',
         'updated_at' => 'datetime',
      ];
   }

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function unidade()
   {
      return $this->belongsTo(AcademiaUnidade::class, 'unidade_id');
   }


   public function treinos()
   {
      return $this->hasManyThrough(
         Treino::class,
         PlanilhaTreino::class,
         'aluno_id',    // FK em planilhas_treino
         'planilha_id', // FK em treinos
         'id',          // id em alunos
         'id'           // id em planilhas_treino
      );
   }

   public function billingAnchorDate(): Carbon
   {
      return Carbon::parse(
         $this->registered_at
            ?? $this->created_at
            ?? $this->user?->created_at
            ?? now()
      )->startOfDay();
   }

   public function nextBillingDate(?CarbonInterface $referenceDate = null): Carbon
   {
      $referenceDate = $referenceDate
         ? Carbon::instance($referenceDate)->startOfDay()
         : now()->startOfDay();

      $billingDay = $this->billingAnchorDate()->day;

      $candidate = $referenceDate->copy();
      $candidate->day(min($billingDay, $candidate->copy()->endOfMonth()->day));

      if ($candidate->lt($referenceDate)) {
         $candidate = $referenceDate->copy()->addMonthNoOverflow()->startOfMonth();
         $candidate->day(min($billingDay, $candidate->copy()->endOfMonth()->day));
      }

      return $candidate;
   }

   public function shouldReceivePaymentReminderOn(?CarbonInterface $referenceDate = null): bool
   {
      $referenceDate = $referenceDate
         ? Carbon::instance($referenceDate)->startOfDay()
         : now()->startOfDay();

      $dueDate = $this->nextBillingDate($referenceDate);

      if (!$dueDate->isSameDay($referenceDate->copy()->addDays(7))) {
         return false;
      }

      return !$this->last_payment_reminder_sent_for?->isSameDay($dueDate);
   }
}
