<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function getFormattedNameAttribute(): string
    {
        return (string) Str::of($this->name)
            ->replace(['.', '_', '-'], ' ')
            ->title();
    }
}
