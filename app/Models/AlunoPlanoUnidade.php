<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlunoPlanoUnidade extends Model
{
   public const PERIODICIDADE_DIARIA = 'diario';
   public const PERIODICIDADE_MENSAL = 'mensal';
   public const PERIODICIDADE_SEMESTRAL = 'semestral';
   public const PERIODICIDADE_ANUAL = 'anual';

   public const PERIODICIDADES = [
      self::PERIODICIDADE_DIARIA,
      self::PERIODICIDADE_MENSAL,
      self::PERIODICIDADE_SEMESTRAL,
      self::PERIODICIDADE_ANUAL,
   ];

   protected $table = 'aluno_plano_unidade';

   protected $fillable = [
      'user_id',
      'academia_unidade_id',
      'plano_id',
      'valor_inicial',
      'valor_total',
      'valor_desconto',
      'forma_pagamento',
      'periodicidade',
      'data_vencimento',
   ];

   protected function casts(): array
   {
      return [
         'data_vencimento' => 'date',
         'valor_inicial' => 'decimal:2',
         'valor_total' => 'decimal:2',
         'valor_desconto' => 'decimal:2',
         'created_at' => 'datetime',
         'updated_at' => 'datetime',
      ];
   }

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }

   public function unidade(): BelongsTo
   {
      return $this->belongsTo(AcademiaUnidade::class, 'academia_unidade_id');
   }

   public function plano(): BelongsTo
   {
      return $this->belongsTo(Planos::class, 'plano_id');
   }

   public function financialTransactions(): HasMany
   {
      return $this->hasMany(FinancialTransaction::class, 'aluno_plano_unidade_id');
   }

   public function periodicidadeLabel(): string
   {
      return match ($this->periodicidade) {
         self::PERIODICIDADE_DIARIA => 'Diario',
         self::PERIODICIDADE_SEMESTRAL => 'Semestral',
         self::PERIODICIDADE_ANUAL => 'Anual',
         default => 'Mensal',
      };
   }

   public function dueDateAnchor(): Carbon
   {
      return Carbon::parse(
         $this->data_vencimento
            ?? $this->created_at
            ?? now()
      )->startOfDay();
   }

   public function nextDueDate(?CarbonInterface $referenceDate = null): Carbon
   {
      $referenceDate = $referenceDate
         ? Carbon::instance($referenceDate)->startOfDay()
         : now()->startOfDay();

      $candidate = $this->dueDateAnchor();

      while ($candidate->lt($referenceDate)) {
         $candidate = $this->advanceDueDate($candidate);
      }

      return $candidate;
   }

   public function nextDueDateFrom(CarbonInterface $baseDate): Carbon
   {
      return $this->advanceDueDate(Carbon::instance($baseDate)->startOfDay());
   }

   public function monetaryDiscount(): float
   {
      return round(((float) $this->valor_inicial) * (((float) $this->valor_desconto) / 100), 2);
   }

   private function advanceDueDate(Carbon $date): Carbon
   {
      return match ($this->periodicidade) {
         self::PERIODICIDADE_DIARIA => $date->copy()->addDay(),
         self::PERIODICIDADE_SEMESTRAL => $date->copy()->addMonthsNoOverflow(6),
         self::PERIODICIDADE_ANUAL => $date->copy()->addYearNoOverflow(),
         default => $date->copy()->addMonthNoOverflow(),
      };
   }
}
