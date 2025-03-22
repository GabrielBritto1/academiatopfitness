<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getFormattedNameAttribute()
    {
        return match ($this->name) {
            'super-admin' => 'Super Administrador',
            'admin' => 'Administrador',
            default => ucfirst($this->name),
        };
    }
}
