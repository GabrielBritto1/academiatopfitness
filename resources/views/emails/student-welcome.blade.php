<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo(a)</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2>Cadastro confirmado, {{ $studentName }}!</h2>

    <p>Seja bem-vindo(a) à Academia Top Fitness.</p>

    <p>Seu cadastro foi realizado com o e-mail <strong>{{ $studentEmail }}</strong>.</p>

    <p>Para acessar o sistema, utilize a tela de login. Se precisar definir ou redefinir sua senha, use a opção de recuperação:</p>

    <p>
        <a href="{{ url('login') }}">Acessar login</a><br>
        <a href="{{ url('password/reset') }}">Recuperar / criar senha</a>
    </p>

    <p>Qualquer dúvida, fale com a academia.</p>

    <p>Academia Top Fitness</p>
</body>
</html>
