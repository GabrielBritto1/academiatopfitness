<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   public function abilities()
   {
      return $this->belongsToMany(Ability::class);
   }

   public function getFormattedNameAttribute()
   {
      return match ($this->name) {
         'admin' => 'Administrador',
         'aluno' => 'Aluno',
         'professor' => 'Professor',
         default => ucfirst($this->name),
      };
   }
}
