<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappInstance extends Model
{
    protected $fillable = [
        'name',
        'base_url',
        'instance_name',
        'api_key',
        'description',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function endpointUrl(): string
    {
        return rtrim($this->base_url, '/') . '/message/sendText/' . $this->instance_name;
    }

    public function getMaskedApiKeyAttribute(): string
    {
        $length = strlen($this->api_key);

        if ($length <= 8) {
            return str_repeat('*', max($length, 4));
        }

        return substr($this->api_key, 0, 4)
            . str_repeat('*', $length - 8)
            . substr($this->api_key, -4);
    }
}
