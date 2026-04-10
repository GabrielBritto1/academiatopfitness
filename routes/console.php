<?php

use App\Services\PaymentReminderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('topfitness:gerar-documentacao {output?}', function (?string $output = null) {
    $outputPath = $output
        ? (str_starts_with($output, '/') ? $output : base_path($output))
        : base_path('docs/academia-top-fitness-documentacao.pdf');

    File::ensureDirectoryExists(dirname($outputPath));

    Pdf::loadView('documentacao.projeto_pdf', [
        'generatedAt' => now()->format('d/m/Y H:i'),
    ])->setPaper('a4')->save($outputPath);

    $this->info("PDF gerado em: {$outputPath}");
})->purpose('Gera o PDF de documentacao detalhada do projeto Academia Top Fitness');

Artisan::command('topfitness:send-payment-reminders {--date=}', function () {
    $referenceDate = $this->option('date')
        ? Carbon::parse($this->option('date'))
        : null;

    $result = app(PaymentReminderService::class)->sendDueSoonReminders($referenceDate);

    $this->info("Processados: {$result['processed']}");
    $this->info("{$result['sent']} email(s) enviado(s)");
    $this->info("{$result['skipped']} registro(s) sem envio");
    $this->info("Data de referencia: {$result['reference_date']}");
})->purpose('Envia lembretes de pagamento para alunos com vencimento em 7 dias');
