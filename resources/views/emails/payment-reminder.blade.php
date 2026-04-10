<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembrete de vencimento</title>
</head>

<body style="margin: 0; padding: 24px 12px; background-color: #f4f1ea; font-family: Arial, Helvetica, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 640px; border-collapse: collapse; background-color: #ffffff; border-radius: 18px; overflow: hidden;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #111827 0%, #2f3a4a 100%); padding: 32px 36px; text-align: center;">
                            <img src="{{ asset('img/logo.png') }}" alt="Academia Top Fitness" style="max-width: 180px; width: 100%; height: auto;">
                            <p style="margin: 18px 0 0; color: #f59e0b; font-size: 12px; letter-spacing: 2px; text-transform: uppercase;">
                                Aviso de vencimento
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 36px;">
                            <p style="margin: 0 0 12px; font-size: 16px;">
                                Ola, <strong>{{ $studentName }}</strong>.
                            </p>

                            <h1 style="margin: 0 0 18px; font-size: 28px; line-height: 1.2; color: #111827;">
                                Sua mensalidade vence em breve
                            </h1>

                            <p style="margin: 0 0 18px; font-size: 16px; line-height: 1.7; color: #4b5563;">
                                Identificamos que a proxima data prevista para pagamento da sua mensalidade e
                                <strong>{{ $dueDate }}</strong>.
                            </p>

                            <p style="margin: 0 0 24px; font-size: 15px; line-height: 1.7; color: #6b7280;">
                                Para manter seu acesso ativo, seus treinos liberados e evitar qualquer interrupcao no uso da academia, pedimos que realize o pagamento ate a data informada.
                            </p>

                            @if ($unitName)
                                <p style="margin: 0 0 24px; font-size: 15px; line-height: 1.7; color: #6b7280;">
                                    Unidade vinculada: <strong>{{ $unitName }}</strong>.
                                </p>
                            @endif

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; background-color: #fff7ed; border: 1px solid #fed7aa; border-radius: 14px;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <p style="margin: 0 0 8px; font-size: 14px; font-weight: bold; color: #9a3412;">
                                            Importante
                                        </p>
                                        <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #7c2d12;">
                                            Caso o pagamento nao seja identificado ate o vencimento, o acesso do aluno podera ser bloqueado conforme as regras administrativas da academia.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 36px 32px;">
                            <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #9ca3af; text-align: center;">
                                {{ config('app.name', 'Academia Top Fitness') }}<br>
                                Mensagem automatica de lembrete financeiro.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
