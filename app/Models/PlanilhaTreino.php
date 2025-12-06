<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanilhaTreino extends Model
{
   protected $fillable = [
      'is_padrao',
      'nome',
      'aluno_id',
      'professor_id',
      'plano_id',
      'unidade_id',
      'observacoes',
   ];

   protected $casts = [
      'is_padrao' => 'boolean',
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

   public function treinos()
   {
      return $this->hasMany(Treino::class, 'planilha_id');
   }
}
