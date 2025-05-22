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
}
