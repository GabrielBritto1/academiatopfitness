<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreinoExercicio extends Model
{
   protected $fillable = [
      'treino_id',
      'nome',
      'series',
      'repeticoes',
      'carga',
      'descanso',
      'observacao',
      'ordem'
   ];

   public function treino()
   {
      return $this->belongsTo(Treino::class);
   }
}
