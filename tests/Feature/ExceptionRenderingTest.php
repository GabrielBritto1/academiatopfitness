<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExceptionRenderingTest extends TestCase
{
    public function test_web_requests_show_custom_error_page_instead_of_laravel_debug_screen(): void
    {
        config(['app.debug' => true]);

        Route::get('/_test-error-web', function () {
            throw new \RuntimeException('Explodiu no navegador');
        });

        $response = $this->get('/_test-error-web');

        $response->assertStatus(500);
        $response->assertSee('Algo saiu do esperado.');
        $response->assertSee('Ocorreu um erro inesperado. Tente novamente em instantes.');
        $response->assertDontSee('Explodiu no navegador');
        $response->assertDontSee('Symfony');
    }

    public function test_api_requests_show_generic_json_error_instead_of_laravel_debug_screen(): void
    {
        config(['app.debug' => true]);

        Route::get('/api/_test-error-api', function () {
            throw new \RuntimeException('Explodiu na API');
        });

        $response = $this->getJson('/api/_test-error-api');

        $response->assertStatus(500);
        $response->assertExactJson([
            'message' => 'Ocorreu um erro inesperado. Tente novamente em instantes.',
        ]);
    }
}
