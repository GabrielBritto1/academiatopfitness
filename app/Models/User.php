<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
   use HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
      'name',
      'email',
      'password',
   ];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
   protected function casts(): array
   {
      return [
         'email_verified_at' => 'datetime',
         'password' => 'hashed',
      ];
   }

   public function roles()
   {
      return $this->belongsToMany(Role::class);
   }

   public function abilities()
   {
      return $this->roles->map->abilities->flatten()->pluck('name');
   }

   public function planos()
   {
      return $this->belongsToMany(Planos::class, 'aluno_plano_unidade', 'user_id', 'plano_id')
         ->withPivot('academia_unidade_id')
         ->withTimestamps();
   }
}
