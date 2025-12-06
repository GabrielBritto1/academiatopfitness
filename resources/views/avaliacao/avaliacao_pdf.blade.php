<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Avaliação Física - {{ $aluno->name }}</title>

   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: DejaVu Sans, sans-serif;
         background: #fff;
         color: #222;
         padding: 40px;
         font-size: 13px;
         line-height: 1.5;
      }

      .header {
         display: flex;
         align-items: center;
         margin-bottom: 25px;
         border-bottom: 2px solid #ccc;
         padding-bottom: 12px;
      }

      .header img {
         width: 70px;
         margin-right: 20px;
      }

      .header .info h1 {
         font-size: 22px;
         font-weight: 600;
         margin-bottom: 4px;
      }

      .section-title {
         font-size: 17px;
         font-weight: 600;
         margin: 25px 0 10px;
         padding-bottom: 6px;
         border-bottom: 1px solid #bbb;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
      }

      th,
      td {
         border: 1px solid #ddd;
         text-align: center;
         padding: 6px;
      }

      th {
         background: #f7f7f7;
         font-weight: bold;
      }

      .obs {
         text-align: left;
         white-space: pre-wrap;
      }
   </style>
</head>

<body>

   {{-- Cabeçalho --}}
   <div class="header">
      <img src="{{ public_path('img/iso logo cor.png') }}" alt="Logo">
      <div class="info">
         <h1>Avaliação Física</h1>
         <div>Aluno: <strong>{{ $aluno->name }}</strong></div>
      </div>
   </div>

   @if($u)

   {{-- Resumo --}}
   <div class="section-title">Resumo Geral</div>

   <table>
      <tr>
         <th>Data</th>
         <th>Professor</th>
         <th>Peso</th>
         <th>Altura</th>
         <th>IMC</th>
         <th>% Gordura</th>
         <th>Massa Muscular (kg)</th>
      </tr>
      <tr>
         <td>{{ $u->created_at->format('d/m/Y') }}</td>
         <td>{{ $u->professor->name }}</td>
         <td>{{ $u->peso }}</td>
         <td>{{ $u->altura }}</td>
         <td>{{ $u->imc }}</td>
         <td>{{ $u->gordura }}</td>
         <td>{{ $u->massa_muscular }}</td>
      </tr>
   </table>


   {{-- PERIMETRIAS --}}
   <div class="section-title">Perimetrias Corporais</div>

   <table>
      <tr>
         <th>Tórax</th>
         <th>Cintura</th>
         <th>Abdômen</th>
         <th>Quadril</th>
      </tr>
      <tr>
         <td>{{ $u->torax }}</td>
         <td>{{ $u->cintura }}</td>
         <td>{{ $u->abdomen_medida }}</td>
         <td>{{ $u->quadril }}</td>
      </tr>
   </table>

   <table>
      <tr>
         <th>Braço Relaxado (E)</th>
         <th>Braço Relaxado (D)</th>
         <th>Braço Contraído (E)</th>
         <th>Braço Contraído (D)</th>
      </tr>
      <tr>
         <td>{{ $u->braco_relaxado_esquerdo }}</td>
         <td>{{ $u->braco_relaxado_direito }}</td>
         <td>{{ $u->braco_contraido_esquerdo }}</td>
         <td>{{ $u->braco_contraido_direito }}</td>
      </tr>
   </table>

   <table>
      <tr>
         <th>Coxa Medial</th>
         <th>Panturrilha</th>
      </tr>
      <tr>
         <td>{{ $u->coxa_medial }}</td>
         <td>{{ $u->panturrilha }}</td>
      </tr>
   </table>


   {{-- DO BRAS (se tiver) --}}
   @if($u->protocolo)
   <div class="section-title">Dobras Cutâneas – {{ strtoupper($u->protocolo) }}</div>

   <table>
      <tr>
         <th>Peito</th>
         <th>Tríceps</th>
         <th>Subescapular</th>
         <th>Axilar Média</th>
         <th>Supra-ilíaca</th>
         <th>Abdômen</th>
         <th>Coxa</th>
      </tr>
      <tr>
         <td>{{ $u->peito ?? '-' }}</td>
         <td>{{ $u->triceps ?? '-' }}</td>
         <td>{{ $u->subescapular ?? '-' }}</td>
         <td>{{ $u->axilar_media ?? '-' }}</td>
         <td>{{ $u->supra_iliaca ?? '-' }}</td>
         <td>{{ $u->abdomen_dobra ?? '-' }}</td>
         <td>{{ $u->coxa_dobra ?? '-' }}</td>
      </tr>
   </table>
   @endif

   {{-- OBS --}}
   <div class="section-title">Observações</div>

   <table>
      <tr>
         <td class="obs">{{ $u->observacoes ?: 'Nenhuma observação registrada.' }}</td>
      </tr>
   </table>

   @endif

</body>

</html>