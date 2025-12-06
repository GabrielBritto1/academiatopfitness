<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <style>
      body {
         font-family: DejaVu Sans, sans-serif;
      }

      h1 {
         margin-bottom: 0;
      }

      h2 {
         margin-top: 40px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 10px;
      }

      th,
      td {
         border: 1px solid #000;
         padding: 6px;
         font-size: 13px;
      }

      th {
         background: #eee;
      }
   </style>
</head>

<body>

   <h1>Ficha de Treino – {{ $aluno->name }}</h1>
   <p>Gerado em: {{ now()->format('d/m/Y') }}</p>

   @foreach($aluno->planilhas as $planilha)
   <h2 style="margin-top: 30px;">Planilha de {{ $planilha->created_at->format('d/m/Y') }}</h2>
   <p><strong>Professor:</strong> {{ $planilha->professor->name ?? '—' }} | <strong>Unidade:</strong> {{ $planilha->unidade->nome ?? '—' }}</p>
   
   @foreach($planilha->treinos as $treino)
   <h2>Treino {{ $treino->sigla }} – {{ $treino->nome }}</h2>
   <p><strong>Dias:</strong> {{ $treino->dias_semana }}</p>

   <table>
      <thead>
         <tr>
            <th>Exercício</th>
            <th>Séries</th>
            <th>Repetições</th>
            <th>Carga</th>
            <th>Descanso</th>
            <th>Obs</th>
         </tr>
      </thead>

      <tbody>
         @foreach($treino->exercicios as $ex)
         <tr>
            <td>{{ $ex->nome }}</td>
            <td>{{ $ex->series }}</td>
            <td>{{ $ex->repeticoes }}</td>
            <td>{{ $ex->carga }}</td>
            <td>{{ $ex->descanso }}</td>
            <td>{{ $ex->observacao }}</td>
         </tr>
         @endforeach
      </tbody>
   </table>

   @if($treino->observacoes)
   <p><strong>Observações do Treino:</strong><br>{{ $treino->observacoes }}</p>
   @endif

   @endforeach
   @endforeach

</body>

</html>