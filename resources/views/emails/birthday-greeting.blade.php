<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Feliz Aniversário</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2>Feliz aniversário, {{ $studentName }}! 🎂</h2>
    <p>{!! nl2br(e($messageBody)) !!}</p>
    <p>Academia Top Fitness</p>
</body>
</html>
