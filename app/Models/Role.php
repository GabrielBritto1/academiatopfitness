<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
   protected $fillable = [
      'name',
      'guard_name',
   ];

   public function getFormattedNameAttribute()
   {
      return match ($this->name) {
         'admin' => 'Administrador',
         'aluno' => 'Aluno',
         'professor' => 'Professor',
         default => (string) Str::of($this->name)->replace(['.', '_', '-'], ' ')->title(),
      };
   }
}
