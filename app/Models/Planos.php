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
}
