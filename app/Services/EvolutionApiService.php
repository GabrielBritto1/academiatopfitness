<?php

namespace App\Services;

use App\Models\WhatsappInstance;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class EvolutionApiService
{
    /**
     * @throws RequestException
     */
    public function sendText(WhatsappInstance $instance, string $phone, string $message): array
    {
        $normalizedPhone = $this->normalizePhone($phone);

        if ($normalizedPhone === '') {
            throw new \InvalidArgumentException('Telefone inválido para envio por WhatsApp.');
        }

        $response = Http::withHeaders([
            'apikey' => $instance->api_key,
        ])->acceptJson()
            ->timeout(15)
            ->post($instance->endpointUrl(), [
                'number' => $normalizedPhone,
                'text' => $message,
            ]);

        $response->throw();

        return $response->json() ?? [];
    }

    public function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        $digits = ltrim($digits, '0');

        if ($digits === '') {
            return '';
        }

        if (! str_starts_with($digits, '55')) {
            $digits = '55' . $digits;
        }

        return $digits;
    }
}
