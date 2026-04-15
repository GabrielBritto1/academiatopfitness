<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planos extends Model
{
   protected $fillable = [
      'name',
      'preco',
      'color',
   ];

   public function beneficios()
   {
      return $this->hasMany(BeneficioPlano::class, 'plano_id')->orderBy('ordem');
   }

   public function unidades()
   {
      return $this->belongsToMany(AcademiaUnidade::class, 'academia_unidade_planos');
   }

   public function alunos()
   {
      return $this->belongsToMany(User::class, 'aluno_plano_unidade', 'plano_id', 'user_id')
         ->withPivot([
            'academia_unidade_id',
            'valor_inicial',
            'valor_total',
            'valor_desconto',
            'forma_pagamento',
            'periodicidade',
            'data_vencimento',
         ])
         ->withTimestamps();
   }

   public function contratos()
   {
      return $this->hasMany(AlunoPlanoUnidade::class, 'plano_id');
   }
}
