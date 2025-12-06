<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
   protected $fillable = [
      'user_id',
      'cpf',
      'telefone',
      'sexo',
      'idade',
      'observacoes',
      'foto',
      'unidade_id',
   ];

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
}
