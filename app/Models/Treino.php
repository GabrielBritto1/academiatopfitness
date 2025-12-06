<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treino extends Model
{
   protected $fillable = [
      'planilha_id',
      'sigla',
      'nome',
      'dias_semana',
      'observacoes'
   ];

   public function exercicios()
   {
      return $this->hasMany(TreinoExercicio::class)->orderBy('ordem');
   }

   public function planilha()
   {
      return $this->belongsTo(PlanilhaTreino::class, 'planilha_id');
   }
}
