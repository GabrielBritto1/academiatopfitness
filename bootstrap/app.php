<?php

use App\Services\PaymentReminderService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(function () {
            app(PaymentReminderService::class)->sendDueSoonReminders();
        })
            ->name('topfitness:send-payment-reminders')
            ->dailyAt('08:00')
            ->timezone('America/Sao_Paulo')
            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $exception, Request $request) {
            if ($exception instanceof ValidationException || $exception instanceof AuthenticationException) {
                return null;
            }

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            $statusCode = $statusCode >= 400 ? $statusCode : 500;

            $message = match ($statusCode) {
                403 => 'Voce nao tem permissao para acessar este recurso.',
                404 => 'A pagina solicitada nao foi encontrada.',
                419 => 'Sua sessao expirou. Tente novamente.',
                default => 'Ocorreu um erro inesperado. Tente novamente em instantes.',
            };

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $message,
                ], $statusCode);
            }

            return response()->view('errors.generic', [
                'statusCode' => $statusCode,
                'message' => $message,
            ], $statusCode);
        });
    })->create();
