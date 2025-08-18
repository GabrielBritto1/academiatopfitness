<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
   protected $fillable = [
      'aluno_id',
      'professor_id',
      'peso',
      'altura',
      'imc',
      'gordura',
      'massa_muscular',
      'circunferencia_cintura',
      'circunferencia_quadril',
      'circunferencia_braco_relaxado',
      'circunferencia_braco_contraido',
      'circunferencia_peito',
      'circunferencia_coxa',
      'circunferencia_panturrilha',
      'observacoes',
   ];

   public function aluno()
   {
      return $this->belongsTo(User::class, 'aluno_id');
   }
   public function professor()
   {
      return $this->belongsTo(User::class, 'professor_id');
   }
}
