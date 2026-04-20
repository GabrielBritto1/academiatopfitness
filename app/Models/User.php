<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
   use HasFactory, Notifiable, HasRoles;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
      'name',
      'email',
      'password',
      'status',
   ];

   protected string $guard_name = 'web';

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
         'status' => 'boolean',
      ];
   }

   public function aluno()
   {
      return $this->hasOne(Aluno::class);
   }

   public function planos()
   {
      return $this->belongsToMany(Planos::class, 'aluno_plano_unidade', 'user_id', 'plano_id')
         ->withPivot([
            'id',
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

   public function planosContratados()
   {
      return $this->hasMany(AlunoPlanoUnidade::class);
   }

   public function avaliacoes()
   {
      return $this->hasMany(Avaliacao::class, 'aluno_id');
   }

   public function planilhas()
   {
      return $this->hasMany(PlanilhaTreino::class, 'aluno_id');
   }

   public function financialTransactions()
   {
      return $this->hasMany(FinancialTransaction::class);
   }

   public function sendPasswordResetNotification($token): void
   {
      $this->notify(new ResetPasswordNotification($token));
   }
}
