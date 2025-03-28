<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficioPlano extends Model
{
    protected $fillable = [
        'plano_id',
        'beneficio',
        'ordem'
    ];
}
