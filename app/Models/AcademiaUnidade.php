<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademiaUnidade extends Model
{
   protected $fillable = [
      'nome',
      'endereco',
   ];

   public function modalidades()
   {
      return $this->belongsToMany(Modalidade::class, 'academia_unidade_modalidades');
   }

   public function alunosModalidades()
   {
      return $this->belongsToMany(User::class, 'aluno_modalidade_unidade')
         ->withPivot('modalidade_id')
         ->withTimestamps();
   }

   public function planos()
   {
      return $this->belongsToMany(Planos::class, 'academia_unidade_planos');
   }
}
