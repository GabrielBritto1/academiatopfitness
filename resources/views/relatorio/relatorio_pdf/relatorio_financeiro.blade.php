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

      .pill {
         display: inline-block;
         padding: 3px 8px;
         border-radius: 999px;
         font-size: 12px;
         border: 1px solid #333;
      }

      .pill-success { background: #d4edda; }
      .pill-warning { background: #fff3cd; }
      .pill-danger { background: #f8d7da; }
      .pill-secondary { background: #e2e3e5; }
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
            <div style="margin-top: 6px; font-size: 12px;">
               <strong>Período:</strong>
               {{ $filters['date_from'] ?? '-' }} até {{ $filters['date_to'] ?? '-' }}
               &nbsp;|&nbsp;
               <strong>Unidade:</strong> {{ $unidadeSelecionada->nome ?? 'Todas' }}
               &nbsp;|&nbsp;
               <strong>Tipo:</strong>
               @if(($filters['kind'] ?? '') === 'conta_receber') Entradas @elseif(($filters['kind'] ?? '') === 'conta_pagar') Saídas @else Todos @endif
               &nbsp;|&nbsp;
               <strong>Status:</strong> {{ $filters['status'] ?? 'Todos' }}
               &nbsp;|&nbsp;
               <strong>Categoria:</strong> {{ $categoriaSelecionada->name ?? 'Todas' }}
               &nbsp;|&nbsp;
               <strong>Pagamento:</strong> {{ $filters['payment_method'] ?? 'Todos' }}
            </div>
         </td>
      </tr>
   </table>

   <table style="width: 100%; margin-bottom: 10px;">
      <tr>
         <td><strong>Receitas pagas:</strong> R$ {{ number_format($totais['receitas_pagas'] ?? 0, 2, ',', '.') }}</td>
         <td><strong>Despesas pagas:</strong> R$ {{ number_format($totais['despesas_pagas'] ?? 0, 2, ',', '.') }}</td>
         <td><strong>Saldo (pago):</strong> R$ {{ number_format($totais['saldo_pago'] ?? 0, 2, ',', '.') }}</td>
         <td><strong>A receber:</strong> R$ {{ number_format($totais['receitas_a_receber'] ?? 0, 2, ',', '.') }}</td>
         <td><strong>A pagar:</strong> R$ {{ number_format($totais['despesas_pendentes'] ?? 0, 2, ',', '.') }}</td>
      </tr>
   </table>

   @if(!empty($resumoPorCategoria))
   <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 16px;">Resumo por Categoria</h3>
   <table style="width: 100%; margin-bottom: 20px;">
      <thead>
         <tr>
            <th style="background: #000; color: #fff; padding: 8px;">Categoria</th>
            <th style="background: #000; color: #fff; padding: 8px;">Tipo</th>
            <th style="background: #000; color: #fff; padding: 8px;">Receitas Pagas</th>
            <th style="background: #000; color: #fff; padding: 8px;">A Receber</th>
            <th style="background: #000; color: #fff; padding: 8px;">Despesas Pagas</th>
            <th style="background: #000; color: #fff; padding: 8px;">A Pagar</th>
            <th style="background: #000; color: #fff; padding: 8px;">Saldo</th>
         </tr>
      </thead>
      <tbody>
         @foreach($resumoPorCategoria as $cat)
         <tr>
            <td style="border: 1px solid #333; padding: 8px;"><strong>{{ $cat['nome'] }}</strong></td>
            <td style="border: 1px solid #333; padding: 8px;">
               @if($cat['tipo'] === 'receita')
                  <span style="color: #28a745;">Receita</span>
               @else
                  <span style="color: #dc3545;">Despesa</span>
               @endif
            </td>
            <td style="border: 1px solid #333; padding: 8px; text-align: right; color: #28a745;">
               @if($cat['receitas_pagas'] > 0)
                  R$ {{ number_format($cat['receitas_pagas'], 2, ',', '.') }}
               @else
                  -
               @endif
            </td>
            <td style="border: 1px solid #333; padding: 8px; text-align: right; color: #ffc107;">
               @if($cat['receitas_a_receber'] > 0)
                  R$ {{ number_format($cat['receitas_a_receber'], 2, ',', '.') }}
               @else
                  -
               @endif
            </td>
            <td style="border: 1px solid #333; padding: 8px; text-align: right; color: #dc3545;">
               @if($cat['despesas_pagas'] > 0)
                  R$ {{ number_format($cat['despesas_pagas'], 2, ',', '.') }}
               @else
                  -
               @endif
            </td>
            <td style="border: 1px solid #333; padding: 8px; text-align: right; color: #ffc107;">
               @if($cat['despesas_pendentes'] > 0)
                  R$ {{ number_format($cat['despesas_pendentes'], 2, ',', '.') }}
               @else
                  -
               @endif
            </td>
            <td style="border: 1px solid #333; padding: 8px; text-align: right; font-weight: bold;">
               @php
                  $saldoCategoria = ($cat['receitas_pagas'] + $cat['receitas_a_receber']) - ($cat['despesas_pagas'] + $cat['despesas_pendentes']);
               @endphp
               @if($saldoCategoria > 0)
                  <span style="color: #28a745;">+ R$ {{ number_format($saldoCategoria, 2, ',', '.') }}</span>
               @elseif($saldoCategoria < 0)
                  <span style="color: #dc3545;">- R$ {{ number_format(abs($saldoCategoria), 2, ',', '.') }}</span>
               @else
                  R$ 0,00
               @endif
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
   @endif

   <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 16px;">Transações Detalhadas</h3>
   <table>
      <thead>
         <tr>
            <th>Data</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Unidade</th>
            <th>Aluno/Fornecedor</th>
            <th>Forma Pgto</th>
            <th>Valor</th>
            <th>Status</th>
         </tr>
      </thead>
      <tbody>
         @forelse($transactions as $t)
         <tr>
            <td>{{ $t->created_at ? $t->created_at->format('d/m/Y') : '-' }}</td>
            <td>{{ $t->kind === 'conta_receber' ? 'Entrada' : 'Saída' }}</td>
            <td>{{ $t->description }}</td>
            <td>{{ $t->category->name ?? '-' }}</td>
            <td>{{ $t->unidade->nome ?? '-' }}</td>
            <td>{{ $t->user->name ?? '-' }}</td>
            <td>{{ $t->payment_method ?? '-' }}</td>
            <td>
               @php($valor = ($t->amount_paid ?? ($t->amount - $t->discount + $t->addition)))
               @if($t->kind === 'conta_receber')
                  + R$ {{ number_format($valor, 2, ',', '.') }}
               @else
                  - R$ {{ number_format($valor, 2, ',', '.') }}
               @endif
            </td>
            <td>
               @if($t->kind === 'conta_receber')
                  @if($t->status === 'pago')
                     <span class="pill pill-success">Recebido</span>
                  @elseif($t->status === 'vencido')
                     <span class="pill pill-danger">A Receber (Vencido)</span>
                  @elseif($t->status === 'cancelado')
                     <span class="pill pill-secondary">Cancelado</span>
                  @else
                     <span class="pill pill-warning">A Receber</span>
                  @endif
               @else
                  @if($t->status === 'pago')
                     <span class="pill pill-success">Pago</span>
                  @elseif($t->status === 'vencido')
                     <span class="pill pill-danger">Vencido</span>
                  @elseif($t->status === 'cancelado')
                     <span class="pill pill-secondary">Cancelado</span>
                  @else
                     <span class="pill pill-warning">Pendente</span>
                  @endif
               @endif
            </td>
         </tr>
         @empty
         <tr>
            <td colspan="9">Nenhuma transação encontrada para os filtros informados.</td>
         </tr>
         @endforelse
      </tbody>
   </table>
</body>

</html>