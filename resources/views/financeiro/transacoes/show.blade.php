@extends('adminlte::page')

@section('title', 'Detalhes da Transação')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-eye"></i> Detalhes da Transação</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="col-md-6">
            <h5><strong>Descrição:</strong> {{ $transaction->description }}</h5>
            <p><strong>Tipo:</strong>
               @if($transaction->kind === 'conta_receber')
               <span class="badge badge-success">Receita</span>
               @else
               <span class="badge badge-danger">Despesa</span>
               @endif
            </p>
            <p><strong>Categoria:</strong> {{ $transaction->category->name ?? '-' }}</p>
            <p><strong>Unidade:</strong> {{ $transaction->unidade->nome ?? '-' }}</p>
            <p><strong>{{ $transaction->kind === 'conta_receber' ? 'Aluno' : 'Fornecedor' }}:</strong> {{ $transaction->user->name ?? '-' }}</p>
         </div>
         <div class="col-md-6">
            <p><strong>Valor:</strong> R$ {{ number_format($transaction->amount, 2, ',', '.') }}</p>
            <p><strong>Desconto:</strong> R$ {{ number_format($transaction->discount, 2, ',', '.') }}</p>
            <p><strong>Acréscimo:</strong> R$ {{ number_format($transaction->addition, 2, ',', '.') }}</p>
            <p><strong>Valor Total:</strong>
               <span class="font-weight-bold {{ $transaction->kind === 'conta_receber' ? 'text-success' : 'text-danger' }}">
                  R$ {{ number_format($transaction->amount - $transaction->discount + $transaction->addition, 2, ',', '.') }}
               </span>
            </p>
            <p><strong>Valor Pago:</strong> {{ $transaction->amount_paid ? 'R$ ' . number_format($transaction->amount_paid, 2, ',', '.') : '-' }}</p>
         </div>
      </div>

      <hr>

      <div class="row">
         <div class="col-md-6">
            <p><strong>Data de Vencimento:</strong> {{ $transaction->due_date ? $transaction->due_date->format('d/m/Y') : '-' }}</p>
            <p><strong>Data de Pagamento:</strong> {{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y') : '-' }}</p>
            <p><strong>Forma de Pagamento:</strong> {{ strtoupper($transaction->payment_method) ?? '-' }}</p>
         </div>
         <div class="col-md-6">
            <p><strong>Status:</strong>
               @if($transaction->status === 'pago')
               <span class="badge badge-success">Pago</span>
               @elseif($transaction->status === 'vencido')
               <span class="badge badge-danger">Vencido</span>
               @elseif($transaction->status === 'cancelado')
               <span class="badge badge-secondary">Cancelado</span>
               @else
               <span class="badge badge-warning">Pendente</span>
               @endif
            </p>
            <p><strong>Data de Criação:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Última Atualização:</strong> {{ $transaction->updated_at->format('d/m/Y H:i') }}</p>
         </div>
      </div>

      <div class="mt-3">
         <a href="{{ $transaction->kind === 'conta_receber' ? route('financeiro.contas-receber.index') : route('financeiro.contas-pagar.index') }}" class="btn btn-secondary">Voltar</a>
         <a href="{{ route('financeiro.transacoes.edit', $transaction->id) }}" class="btn btn-warning">Editar</a>
         @if($transaction->status === 'pendente')
         <form action="{{ route('financeiro.transacoes.marcar-pago', $transaction->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">Marcar como Pago</button>
         </form>
         @endif
      </div>
   </div>
</div>
@stop