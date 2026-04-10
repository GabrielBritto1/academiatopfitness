@extends('adminlte::page')

@section('title', 'Contas a Receber')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-hand-holding-usd"></i> Contas a Receber</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <a href="{{ route('financeiro.transacoes.create', ['kind' => 'conta_receber']) }}" class="btn btn-sm btn-success">
            <i class="fas fa-fw fa-plus"></i> Nova Conta a Receber
         </a>
         <a href="{{ route('financeiro.caixa.index') }}" class="btn btn-sm btn-info">
            <i class="fas fa-fw fa-cash-register"></i> Caixa
         </a>
      </div>
   </div>
   <div class="card-body">
      <!-- Filtros -->
      <form method="GET" action="{{ route('financeiro.contas-receber.index') }}" class="mb-3">
         <div class="row">
            <div class="col-md-4">
               <select name="status" class="form-control">
                  <option value="">Todos os status</option>
                  <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                  <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                  <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Vencido</option>
               </select>
            </div>
            <div class="col-md-4">
               <select name="unidade_id" class="form-control">
                  <option value="">Todas as unidades</option>
                  @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}" {{ request('unidade_id') == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-4">
               <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
         </div>
      </form>

      <div class="table-responsive">
         <table class="table table-hover text-nowrap">
            <thead>
               <tr>
                  <th>Descrição</th>
                  <th>Aluno</th>
                  <th>Unidade</th>
                  <th>Vencimento</th>
                  <th>Valor</th>
                  <th>Status</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @forelse($transactions as $transaction)
               <tr>
                  <td>{{ $transaction->description }}</td>
                  <td>{{ $transaction->user->name ?? '-' }}</td>
                  <td>{{ $transaction->unidade->nome ?? '-' }}</td>
                  <td>{{ $transaction->due_date ? $transaction->due_date->format('d/m/Y') : '-' }}</td>
                  <td class="text-success font-weight-bold">R$ {{ number_format($transaction->amount - $transaction->discount + $transaction->addition, 2, ',', '.') }}</td>
                  <td>
                     @if($transaction->status === 'pago')
                     <span class="badge badge-success">Recebido</span>
                     @elseif($transaction->status === 'vencido')
                     <span class="badge badge-danger">A Receber (Vencido)</span>
                     @elseif($transaction->status === 'cancelado')
                     <span class="badge badge-secondary">Cancelado</span>
                     @else
                     <span class="badge badge-warning">A Receber</span>
                     @endif
                  </td>
                  <td>
                     @if($transaction->status === 'pendente')
                     <form action="{{ route('financeiro.transacoes.marcar-pago', $transaction->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" title="Marcar como pago">
                           <i class="fas fa-check"></i>
                        </button>
                     </form>
                     @endif
                     <a href="{{ route('financeiro.transacoes.show', $transaction->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i>
                     </a>
                     <a href="{{ route('financeiro.transacoes.edit', $transaction->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                     </a>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="7" class="text-center">Nenhuma conta a receber encontrada.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>

      <div class="mt-3">
         {{ $transactions->links() }}
      </div>
   </div>
</div>
@stop
