<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Avaliações Físicas - {{ $aluno->name }}</title>

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
         padding: 20px;
         font-size: 12px;
         line-height: 1.4;
      }

      .header {
         text-align: center;
         margin-bottom: 20px;
         border-bottom: 2px solid #333;
         padding-bottom: 10px;
      }

      .header h1 {
         font-size: 20px;
         font-weight: 600;
         margin-bottom: 5px;
      }

      .filtros {
         font-size: 11px;
         color: #666;
         margin-bottom: 15px;
      }

      .avaliacao {
         page-break-inside: avoid;
         margin-bottom: 30px;
         border: 1px solid #ddd;
         padding: 15px;
         border-radius: 5px;
      }

      .avaliacao-header {
         background: #f5f5f5;
         padding: 10px;
         margin: -15px -15px 15px -15px;
         border-bottom: 1px solid #ddd;
      }

      .avaliacao-header h2 {
         font-size: 16px;
         font-weight: 600;
         margin-bottom: 5px;
      }

      .avaliacao-header .data {
         font-size: 11px;
         color: #666;
      }

      .section-title {
         font-size: 14px;
         font-weight: 600;
         margin: 15px 0 8px;
         padding-bottom: 4px;
         border-bottom: 1px solid #bbb;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 15px;
         font-size: 11px;
      }

      table th,
      table td {
         border: 1px solid #ddd;
         padding: 6px 8px;
         text-align: left;
      }

      table th {
         background: #f0f0f0;
         font-weight: 600;
      }

      .row {
         display: flex;
         margin-bottom: 8px;
      }

      .col {
         flex: 1;
         padding: 0 5px;
      }

      .col-2 {
         flex: 2;
      }

      .label {
         font-weight: 600;
         margin-right: 5px;
      }

      .observacoes {
         margin-top: 15px;
         padding: 10px;
         background: #f9f9f9;
         border-left: 3px solid #333;
      }
   </style>
</head>

<body>
   <div class="header">
      <h1>Avaliações Físicas - {{ $aluno->name }}</h1>
      <div class="filtros">
         <strong>Filtros aplicados:</strong>
         @php
            $filtrosTexto = [];
            if (!empty($filtros['ano'])) {
               $filtrosTexto[] = 'Ano: ' . $filtros['ano'];
            }
            if (!empty($filtros['mes'])) {
               $meses = [
                  1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                  5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                  9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
               ];
               $filtrosTexto[] = 'Mês: ' . ($meses[(int)$filtros['mes']] ?? $filtros['mes']);
            }
            if (!empty($filtros['dia'])) {
               $filtrosTexto[] = 'Dia: ' . str_pad($filtros['dia'], 2, '0', STR_PAD_LEFT);
            }
         @endphp
         @if(count($filtrosTexto) > 0)
         {{ implode(' | ', $filtrosTexto) }}
         @else
         Todas as avaliações
         @endif
         <br>
         <strong>Total de avaliações:</strong> {{ $avaliacoes->count() }}
      </div>
   </div>

   @foreach($avaliacoes as $index => $u)
   <div class="avaliacao">
      <div class="avaliacao-header">
         <h2>Avaliação {{ $index + 1 }}</h2>
         <div class="data">
            Data: {{ $u->created_at->format('d/m/Y H:i') }} | 
            Professor: {{ $u->professor->name ?? '—' }}
         </div>
      </div>

      {{-- MEDIDAS BÁSICAS --}}
      <div class="section-title">Medidas Básicas</div>
      <table>
         <tr>
            <th>Peso (Kg)</th>
            <th>Altura (cm)</th>
            <th>IMC</th>
            <th>Gordura (%)</th>
            <th>Massa Muscular</th>
         </tr>
         <tr>
            <td>{{ $u->peso ?? '—' }}</td>
            <td>{{ $u->altura ?? '—' }}</td>
            <td>{{ $u->imc ?? '—' }}</td>
            <td>{{ $u->gordura ?? '—' }}</td>
            <td>{{ $u->massa_muscular ?? '—' }}</td>
         </tr>
      </table>

      {{-- PERIMETRIAS --}}
      <div class="section-title">Perimetrias (cm)</div>
      <table>
         <tr>
            <th>Tórax</th>
            <th>Cintura</th>
            <th>Abdômen</th>
            <th>Quadril</th>
         </tr>
         <tr>
            <td>{{ $u->torax ?? '—' }}</td>
            <td>{{ $u->cintura ?? '—' }}</td>
            <td>{{ $u->abdomen_medida ?? '—' }}</td>
            <td>{{ $u->quadril ?? '—' }}</td>
         </tr>
      </table>

      <table>
         <tr>
            <th>Braço Relaxado Esq.</th>
            <th>Braço Relaxado Dir.</th>
            <th>Braço Contraído Esq.</th>
            <th>Braço Contraído Dir.</th>
         </tr>
         <tr>
            <td>{{ $u->braco_relaxado_esquerdo ?? '—' }}</td>
            <td>{{ $u->braco_relaxado_direito ?? '—' }}</td>
            <td>{{ $u->braco_contraido_esquerdo ?? '—' }}</td>
            <td>{{ $u->braco_contraido_direito ?? '—' }}</td>
         </tr>
      </table>

      <table>
         <tr>
            <th>Coxa Medial</th>
            <th>Panturrilha</th>
         </tr>
         <tr>
            <td>{{ $u->coxa_medial ?? '—' }}</td>
            <td>{{ $u->panturrilha ?? '—' }}</td>
         </tr>
      </table>

      {{-- DOBRAS CUTÂNEAS --}}
      @if($u->peito || $u->triceps || $u->subescapular || $u->axilar_media || $u->supra_iliaca || $u->abdomen_dobra || $u->coxa_dobra)
      <div class="section-title">Dobras Cutâneas (mm)</div>
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
            <td>{{ $u->peito ?? '—' }}</td>
            <td>{{ $u->triceps ?? '—' }}</td>
            <td>{{ $u->subescapular ?? '—' }}</td>
            <td>{{ $u->axilar_media ?? '—' }}</td>
            <td>{{ $u->supra_iliaca ?? '—' }}</td>
            <td>{{ $u->abdomen_dobra ?? '—' }}</td>
            <td>{{ $u->coxa_dobra ?? '—' }}</td>
         </tr>
      </table>
      @endif

      {{-- OBSERVAÇÕES --}}
      @if($u->observacoes)
      <div class="observacoes">
         <strong>Observações:</strong><br>
         {{ $u->observacoes }}
      </div>
      @endif
   </div>
   @endforeach

</body>

</html>

