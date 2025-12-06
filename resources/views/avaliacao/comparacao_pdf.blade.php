<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <title>Comparação de Avaliações - {{ $aluno->name }}</title>

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
         padding: 15px;
         font-size: 10px;
         line-height: 1.3;
      }

      .header {
         text-align: center;
         margin-bottom: 15px;
         border-bottom: 2px solid #333;
         padding-bottom: 8px;
      }

      .header h1 {
         font-size: 18px;
         font-weight: 600;
         margin-bottom: 5px;
      }

      .header .info {
         font-size: 9px;
         color: #666;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 10px;
         font-size: 9px;
      }

      table th,
      table td {
         border: 1px solid #333;
         padding: 5px 4px;
         text-align: center;
      }

      table th {
         background: #f0f0f0;
         font-weight: 600;
         font-size: 8px;
      }

      .row-header {
         background: #e8e8e8 !important;
         font-weight: 600;
         text-align: left !important;
         padding-left: 8px !important;
      }

      .section-header {
         background: #d0d0d0 !important;
         font-weight: 700;
         text-align: center !important;
      }

      .empty-cell {
         color: #999;
         font-style: italic;
      }
   </style>
</head>

<body>
   <div class="header">
      <h1>Comparação de Avaliações Físicas</h1>
      <div class="info">
         <strong>Aluno:</strong> {{ $aluno->name }} | 
         <strong>Total de avaliações comparadas:</strong> {{ $avaliacoes->count() }}
      </div>
   </div>

   @php
      $colWidth = round(80 / $avaliacoes->count(), 1);
   @endphp
   <table>
      {{-- CABEÇALHO COM DATAS --}}
      <thead>
         <tr>
            <th class="row-header" style="width: 20%;">Medidas</th>
            @foreach($avaliacoes as $avaliacao)
            <th style="width: {{ $colWidth }}%;">
               {{ $avaliacao->created_at->format('d/m/Y') }}<br>
               <small style="font-size: 7px;">{{ $avaliacao->created_at->format('H:i') }}</small>
            </th>
            @endforeach
         </tr>
      </thead>
      <tbody>
         {{-- MEDIDAS BÁSICAS --}}
         <tr>
            <td class="section-header" colspan="{{ $avaliacoes->count() + 1 }}">MEDIDAS BÁSICAS</td>
         </tr>
         <tr>
            <td class="row-header">Peso (Kg)</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->peso ? 'empty-cell' : '' }}">{{ $avaliacao->peso ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Altura (cm)</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->altura ? 'empty-cell' : '' }}">{{ $avaliacao->altura ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">IMC</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->imc ? 'empty-cell' : '' }}">{{ $avaliacao->imc ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Gordura (%)</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->gordura ? 'empty-cell' : '' }}">{{ $avaliacao->gordura ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Massa Muscular (kg)</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->massa_muscular ? 'empty-cell' : '' }}">{{ $avaliacao->massa_muscular ?? '—' }}</td>
            @endforeach
         </tr>

         {{-- PERIMETRIAS --}}
         <tr>
            <td class="section-header" colspan="{{ $avaliacoes->count() + 1 }}">PERIMETRIAS (cm)</td>
         </tr>
         <tr>
            <td class="row-header">Tórax</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->torax ? 'empty-cell' : '' }}">{{ $avaliacao->torax ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Cintura</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->cintura ? 'empty-cell' : '' }}">{{ $avaliacao->cintura ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Abdômen</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->abdomen_medida ? 'empty-cell' : '' }}">{{ $avaliacao->abdomen_medida ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Quadril</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->quadril ? 'empty-cell' : '' }}">{{ $avaliacao->quadril ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Braço Relaxado Esquerdo</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->braco_relaxado_esquerdo ? 'empty-cell' : '' }}">{{ $avaliacao->braco_relaxado_esquerdo ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Braço Relaxado Direito</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->braco_relaxado_direito ? 'empty-cell' : '' }}">{{ $avaliacao->braco_relaxado_direito ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Braço Contraído Esquerdo</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->braco_contraido_esquerdo ? 'empty-cell' : '' }}">{{ $avaliacao->braco_contraido_esquerdo ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Braço Contraído Direito</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->braco_contraido_direito ? 'empty-cell' : '' }}">{{ $avaliacao->braco_contraido_direito ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Coxa Medial</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->coxa_medial ? 'empty-cell' : '' }}">{{ $avaliacao->coxa_medial ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Panturrilha</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->panturrilha ? 'empty-cell' : '' }}">{{ $avaliacao->panturrilha ?? '—' }}</td>
            @endforeach
         </tr>

         {{-- DOBRAS CUTÂNEAS --}}
         <tr>
            <td class="section-header" colspan="{{ $avaliacoes->count() + 1 }}">DOBRAS CUTÂNEAS (mm)</td>
         </tr>
         <tr>
            <td class="row-header">Peito</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->peito ? 'empty-cell' : '' }}">{{ $avaliacao->peito ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Tríceps</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->triceps ? 'empty-cell' : '' }}">{{ $avaliacao->triceps ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Subescapular</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->subescapular ? 'empty-cell' : '' }}">{{ $avaliacao->subescapular ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Axilar Média</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->axilar_media ? 'empty-cell' : '' }}">{{ $avaliacao->axilar_media ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Supra-ilíaca</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->supra_iliaca ? 'empty-cell' : '' }}">{{ $avaliacao->supra_iliaca ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Abdômen</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->abdomen_dobra ? 'empty-cell' : '' }}">{{ $avaliacao->abdomen_dobra ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Coxa</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->coxa_dobra ? 'empty-cell' : '' }}">{{ $avaliacao->coxa_dobra ?? '—' }}</td>
            @endforeach
         </tr>

         {{-- INFORMAÇÕES ADICIONAIS --}}
         <tr>
            <td class="section-header" colspan="{{ $avaliacoes->count() + 1 }}">INFORMAÇÕES ADICIONAIS</td>
         </tr>
         <tr>
            <td class="row-header">Professor</td>
            @foreach($avaliacoes as $avaliacao)
            <td style="font-size: 8px;">{{ $avaliacao->professor->name ?? '—' }}</td>
            @endforeach
         </tr>
         <tr>
            <td class="row-header">Protocolo</td>
            @foreach($avaliacoes as $avaliacao)
            <td class="{{ !$avaliacao->protocolo ? 'empty-cell' : '' }}">{{ $avaliacao->protocolo ?? '—' }}</td>
            @endforeach
         </tr>
      </tbody>
   </table>

   {{-- OBSERVAÇÕES --}}
   @if($avaliacoes->where('observacoes', '!=', null)->count() > 0)
   <div style="margin-top: 15px; page-break-inside: avoid;">
      <h3 style="font-size: 11px; margin-bottom: 8px; border-bottom: 1px solid #333; padding-bottom: 3px;">OBSERVAÇÕES</h3>
      @foreach($avaliacoes as $avaliacao)
      @if($avaliacao->observacoes)
      <div style="margin-bottom: 8px; padding: 5px; background: #f5f5f5; border-left: 3px solid #333;">
         <strong style="font-size: 9px;">{{ $avaliacao->created_at->format('d/m/Y') }}:</strong>
         <span style="font-size: 8px;">{{ $avaliacao->observacoes }}</span>
      </div>
      @endif
      @endforeach
   </div>
   @endif

</body>

</html>

