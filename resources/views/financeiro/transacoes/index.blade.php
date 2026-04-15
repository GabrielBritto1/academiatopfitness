@extends('adminlte::page')

@section('title', 'Caixa / Fluxo de Caixa')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-cash-register"></i> Caixa / Fluxo de Caixa</h1>
@stop

@section('content')
<!-- Cards de Resumo -->
<div class="row">
   <div class="col-lg-4 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3>R$ {{ number_format($totalReceitas, 2, ',', '.') }}</h3>
            <p>Total Receitas</p>
         </div>
         <div class="icon">
            <i class="fas fa-arrow-up"></i>
         </div>
      </div>
   </div>
   <div class="col-lg-4 col-6">
      <div class="small-box bg-danger">
         <div class="inner">
            <h3>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</h3>
            <p>Total Despesas</p>
         </div>
         <div class="icon">
            <i class="fas fa-arrow-down"></i>
         </div>
      </div>
   </div>
   <div class="col-lg-4 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3>R$ {{ number_format($saldo, 2, ',', '.') }}</h3>
            <p>Saldo</p>
         </div>
         <div class="icon">
            <i class="fas fa-balance-scale"></i>
         </div>
      </div>
   </div>
</div>

<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <a href="{{ route('financeiro.transacoes.create', ['kind' => 'conta_receber']) }}" class="btn btn-sm btn-success">
            <i class="fas fa-fw fa-plus"></i> Nova Receita
         </a>
         <a href="{{ route('financeiro.transacoes.create', ['kind' => 'conta_pagar']) }}" class="btn btn-sm btn-danger">
            <i class="fas fa-fw fa-minus"></i> Nova Despesa
         </a>
         <a href="{{ route('financeiro.contas-receber.index') }}" class="btn btn-sm btn-info">
            <i class="fas fa-fw fa-list"></i> Contas a Receber
         </a>
         <a href="{{ route('financeiro.contas-pagar.index') }}" class="btn btn-sm btn-warning">
            <i class="fas fa-fw fa-list"></i> Contas a Pagar
         </a>
         <a href="{{ route('financeiro.categorias.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-fw fa-tags"></i> Categorias
         </a>
      </div>
   </div>
   <div class="card-body">
      <!-- Filtros -->
      <form method="GET" action="{{ route('financeiro.caixa.index') }}" class="mb-3">
         <div class="row">
            <div class="col-md-3">
               <select name="kind" class="form-control">
                  <option value="">Todos os tipos</option>
                  <option value="conta_receber" {{ request('kind') == 'conta_receber' ? 'selected' : '' }}>Receitas</option>
                  <option value="conta_pagar" {{ request('kind') == 'conta_pagar' ? 'selected' : '' }}>Despesas</option>
               </select>
            </div>
            <div class="col-md-3">
               <select name="status" class="form-control">
                  <option value="">Todos os status</option>
                  <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                  <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                  <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Vencido</option>
               </select>
            </div>
            <div class="col-md-2">
               <select name="unidade_id" class="form-control">
                  <option value="">Todas as unidades</option>
                  @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}" {{ request('unidade_id') == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-2">
               <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Data inicial">
            </div>
            <div class="col-md-2">
               <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Data final">
               <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
            </div>
         </div>
      </form>

      <div class="table-responsive">
         <table class="table table-hover text-nowrap">
            <thead>
               <tr>
                  <th>Data</th>
                  <th>Tipo</th>
                  <th>Descrição</th>
                  <th>Categoria</th>
                  <th>Aluno/Fornecedor</th>
                  <th>Valor</th>
                  <th>Status</th>
                  <th>Ações</th>
               </tr>
            </thead>
            <tbody>
               @forelse($transactions as $transaction)
               <tr>
                  <td>{{ $transaction->updated_at->format('d/m/Y') }}</td>
                  <td>
                     @if($transaction->kind === 'conta_receber')
                     <span class="badge badge-success">Receita</span>
                     @else
                     <span class="badge badge-danger">Despesa</span>
                     @endif
                  </td>
                  <td>{{ $transaction->description }}</td>
                  <td>{{ $transaction->category->name ?? '-' }}</td>
                  <td>{{ $transaction->user->name ?? '-' }}</td>
                  <td>
                     @if($transaction->kind === 'conta_receber')
                     <span class="text-success">+ R$ {{ number_format($transaction->amount - $transaction->discount + $transaction->addition, 2, ',', '.') }}</span>
                     @else
                     <span class="text-danger">- R$ {{ number_format($transaction->amount - $transaction->discount + $transaction->addition, 2, ',', '.') }}</span>
                     @endif
                  </td>
                  <td>
                     @if($transaction->kind === 'conta_receber')
                     @if($transaction->status === 'pago')
                     <span class="badge badge-success">Recebido</span>
                     @elseif($transaction->status === 'vencido')
                     <span class="badge badge-danger">A Receber (Vencido)</span>
                     @elseif($transaction->status === 'cancelado')
                     <span class="badge badge-secondary">Cancelado</span>
                     @else
                     <span class="badge badge-warning">A Receber</span>
                     @endif
                     @else
                     @if($transaction->status === 'pago')
                     <span class="badge badge-success">Pago</span>
                     @elseif($transaction->status === 'vencido')
                     <span class="badge badge-danger">Vencido</span>
                     @elseif($transaction->status === 'cancelado')
                     <span class="badge badge-secondary">Cancelado</span>
                     @else
                     <span class="badge badge-warning">Pendente</span>
                     @endif
                     @endif
                  </td>
                  <td>
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
                  <td colspan="8" class="text-center">Nenhuma transação encontrada.</td>
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
