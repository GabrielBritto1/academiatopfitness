<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Relatório Financeiro</title>
   <style>
      body {
         font-family: Arial, Helvetica, sans-serif;
         font-size: 14px;
         margin: 20px;
      }

      .logo {
         width: 50px;
         margin-bottom: 5px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      th,
      td {
         border: 1px solid #333;
         padding: 8px;
         text-align: left;
      }

      th {
         background: #000;
         color: #fff;
      }

      tr:nth-child(even) {
         background: #f2f2f2;
      }

      tr:nth-child(odd) {
         background: #fff;
      }

      h2 {
         margin: 0;
      }

      .header {
         display: flex;
         align-items: center;
         gap: 20px;
         margin-bottom: 20px;
      }
   </style>
</head>

<body>
   <table style="width: 100%; margin-bottom: 20px;">
      <tr>
         <td style="width: 60px;">
            <img src="{{ public_path('img/iso logo mono.png') }}" alt="Iso Logo" class="logo" style="width: 50px;">
         </td>
         <td style="vertical-align: middle;">
            <h2 style="margin: 0;">Relatório Financeiro</h2>
         </td>
      </tr>
   </table>
   <table>
      <thead>
         <tr>
            <th>Nome</th>
            <th>Email</th>
            <!-- Adicione mais colunas se necessário -->
         </tr>
      </thead>
      <tbody>
         @forelse($users as $user)
         <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <!-- Adicione mais colunas se necessário -->
         </tr>
         @empty
         <tr>
            <td colspan="2">Nenhum usuário cadastrado.</td>
         </tr>
         @endforelse
      </tbody>
   </table>
</body>

</html>