<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanilhaTreino extends Model
{
   protected $fillable = [
      'aluno_id',
      'professor_id',
      'plano_id',
      'unidade_id',
   ];

   public function aluno()
   {
      return $this->belongsTo(User::class, 'aluno_id');
   }

   public function professor()
   {
      return $this->belongsTo(User::class, 'professor_id');
   }

   public function plano()
   {
      return $this->belongsTo(Planos::class);
   }

   public function unidade()
   {
      return $this->belongsTo(AcademiaUnidade::class);
   }
}
