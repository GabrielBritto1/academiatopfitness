<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidade extends Model
{
    protected $fillable = [
        'name',
        'descricao',
        'duracao',
        'nivel_dificuldade',
        'status'
    ];
}
