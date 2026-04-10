<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialCategory extends Model
{
   protected $fillable = [
      'name',
      'type',
      'is_active',
   ];

   /**
    * Transações ligadas a esta categoria.
    */
   public function transactions(): HasMany
   {
      return $this->hasMany(FinancialTransaction::class);
   }
}


