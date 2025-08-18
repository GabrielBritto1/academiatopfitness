<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Avaliação</title>
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         background: #fff;
         color: #222;
         line-height: 1.6;
      }

      header {
         padding: 40px;
      }

      h1 {
         text-align: left;
         font-size: 24px;
         font-weight: 500;
      }

      .img-fluid {
         width: 50px;
         height: auto;
      }

      .dia {
         font-size: 18px;
         font-weight: 600;
         margin: 30px 0 10px;
         border-bottom: 1px solid #ccc;
         padding-bottom: 4px;
         color: #333;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin: 0 5px 0 5px;
      }

      th,
      td {
         border: 1px solid #ddd;
         text-align: center;
         padding: 8px;
         font-size: 14px;
      }

      th {
         background: #f1f1f1;
      }
   </style>
</head>

<body>
   <header>
      <div class="center">
         <h1><img src="{{ public_path('img/iso logo cor.png') }}" alt="Logo Top Fitness" class="img-fluid"> Avaliação do aluno: {{ $aluno->name }}</h1>
      </div>
   </header>
   <table>
      <thead>
         <tr>
            <th>Data da Avaliação</th>
            <th>Professor Avaliador</th>
            <th>Peso</th>
            <th>Altura</th>
            <th>IMC</th>
            <th>Gordura Corporal</th>
            <th>Massa Muscular</th>
            <th>Cintura</th>
            <th>Quadril</th>
            <th>Braço Relaxado</th>
            <th>Braço Contraído</th>
            <th>Peito</th>
            <th>Coxa</th>
            <th>Panturrilha</th>
            <th>Observação</th>
         </tr>
      </thead>
      <tbody>
         @foreach($avaliacoes as $avaliacao)
         <tr>
            <td>{{ $avaliacao->created_at->format('d/m/Y') }}</td>
            <td>{{ $avaliacao->professor->name }}</td>
            <td>{{ $avaliacao->peso }}</td>
            <td>{{ $avaliacao->altura }}</td>
            <td>{{ $avaliacao->imc }}</td>
            <td>{{ $avaliacao->gordura }}</td>
            <td>{{ $avaliacao->massa_muscular }}</td>
            <td>{{ $avaliacao->circunferencia_cintura }}</td>
            <td>{{ $avaliacao->circunferencia_quadril }}</td>
            <td>{{ $avaliacao->circunferencia_braco_relaxado }}</td>
            <td>{{ $avaliacao->circunferencia_braco_contraido }}</td>
            <td>{{ $avaliacao->circunferencia_peito }}</td>
            <td>{{ $avaliacao->circunferencia_coxa }}</td>
            <td>{{ $avaliacao->circunferencia_panturrilha }}</td>
            <td>{{ $avaliacao->observacoes }}</td>
         </tr>
         @endforeach
      </tbody>
   </table>
</body>