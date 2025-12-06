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
      'massa_muscular',
      'torax',
      'cintura',
      'abdomen_medida',
      'quadril',
      'braco_relaxado_esquerdo',
      'braco_relaxado_direito',
      'braco_contraido_esquerdo',
      'braco_contraido_direito',
      'coxa_medial',
      'panturrilha',
      'peito',
      'triceps',
      'subescapular',
      'axilar_media',
      'supra_iliaca',
      'abdomen_dobra',
      'coxa_dobra',
      'protocolo',
      'sexo_avaliacao',
      'soma_dobras',
      'densidade',
      'gordura',
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
