<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erro inesperado</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f5f7fb;
            --panel: #ffffff;
            --text: #162033;
            --muted: #667085;
            --accent: #d43f3a;
            --border: #d9e2f2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top, rgba(212, 63, 58, 0.12), transparent 30%),
                linear-gradient(180deg, #f9fbff 0%, var(--bg) 100%);
            color: var(--text);
        }

        main {
            width: min(100%, 520px);
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(212, 63, 58, 0.1);
            color: var(--accent);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        h1 {
            margin: 20px 0 12px;
            font-size: clamp(28px, 5vw, 38px);
            line-height: 1.1;
        }

        p {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            color: var(--muted);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        a,
        button {
            border: 0;
            border-radius: 12px;
            padding: 12px 18px;
            font: inherit;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .primary {
            background: #101828;
            color: #ffffff;
        }

        .secondary {
            background: #eef2f8;
            color: var(--text);
        }
    </style>
</head>
<body>
    <main>
        <span class="badge">Erro {{ $statusCode }}</span>
        <h1>Algo saiu do esperado.</h1>
        <p>{{ $message }}</p>

        <div class="actions">
            <button class="primary" type="button" onclick="window.location.reload()">Tentar novamente</button>
            <a class="secondary" href="{{ url('/') }}">Voltar ao inicio</a>
        </div>
    </main>
</body>
</html>
