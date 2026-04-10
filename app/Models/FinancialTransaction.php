<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
   protected $fillable = [
      'kind',
      'financial_category_id',
      'academia_unidade_id',
      'user_id',
      'aluno_plano_unidade_id',
      'description',
      'due_date',
      'paid_at',
      'amount',
      'discount',
      'addition',
      'amount_paid',
      'payment_method',
      'status',
   ];

   protected $casts = [
      'due_date' => 'date',
      'paid_at' => 'date',
   ];

   public function category(): BelongsTo
   {
      return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
   }

   public function unidade(): BelongsTo
   {
      return $this->belongsTo(AcademiaUnidade::class, 'academia_unidade_id');
   }

   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class);
   }
}


