<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Planilha de Treino</title>
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         background: #fff;
         color: #222;
         padding: 40px;
         line-height: 1.6;
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
         margin-bottom: 30px;
      }

      th,
      td {
         border: 1px solid #ddd;
         padding: 10px 12px;
         text-align: left;
         font-size: 14px;
      }

      th {
         background: #f1f1f1;
         font-weight: 600;
      }

      .aluno,
      .data {
         display: block;
         text-align: right;
         font-size: 18px;
         font-weight: 500;
      }

      .center {
         display: flex;
      }
   </style>
</head>

<body>
   <header>
      <div class="center">
         <h1><img src="{{ public_path('img/iso logo cor.png') }}" alt="Logo Top Fitness" class="img-fluid"> Planilha de Treino</h1>
         <span class="aluno">Professor: {{ $professor->name }}</span>
         <span class="data">Data: {{ date('d/m/Y') }}</span>
      </div>
   </header>

   <div class="dia">
      Aluno: {{ $aluno->name }} -
      Treino A
   </div>
   <table>
      <thead>
         <tr>
            <th>Exercício</th>
            <th>Séries</th>
            <th>Repetições</th>
            <th>Descanso</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <td>Agachamento</td>
            <td>4</td>
            <td>10</td>
            <td>60s</td>
         </tr>
         <tr>
            <td>Leg Press</td>
            <td>3</td>
            <td>12</td>
            <td>60s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
         <tr>
            <td>Extensora</td>
            <td>3</td>
            <td>15</td>
            <td>45s</td>
         </tr>
      </tbody>
   </table>
</body>

</html>