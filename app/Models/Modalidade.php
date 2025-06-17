<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
   protected $fillable = [
      'name',
      'descricao',
      'duracao',
      'nivel_dificuldade',
      'status'
   ];
   public function academiaUnidades()
   {
      return $this->belongsToMany(AcademiaUnidade::class, 'academia_unidade_modalidades');
   }

   public function alunosUnidades()
   {
      return $this->belongsToMany(User::class, 'aluno_plano_unidade')
         ->withPivot('academia_unidade_id')
         ->withTimestamps();
   }
}
