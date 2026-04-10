@extends('adminlte::page')

@section('title', 'Editar Transação')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-edit"></i> Editar Transação</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('financeiro.transacoes.update', $transaction->id) }}" method="POST">
         @csrf
         @method('PUT')
         <input type="hidden" name="kind" value="{{ $transaction->kind }}">

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="description">Descrição *</label>
                  <input type="text" class="form-control" id="description" name="description" value="{{ $transaction->description }}" required>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="financial_category_id">Categoria</label>
                  <select class="form-control" id="financial_category_id" name="financial_category_id">
                     <option value="">Selecione uma categoria</option>
                     @foreach($categories as $category)
                     <option value="{{ $category->id }}" {{ $transaction->financial_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="academia_unidade_id">Unidade</label>
                  <select class="form-control" id="academia_unidade_id" name="academia_unidade_id">
                     <option value="">Selecione uma unidade</option>
                     @foreach($unidades as $unidade)
                     <option value="{{ $unidade->id }}" {{ $transaction->academia_unidade_id == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="user_id">{{ $transaction->kind === 'conta_receber' ? 'Aluno' : 'Fornecedor' }}</label>
                  <select class="form-control" id="user_id" name="user_id">
                     <option value="">Selecione</option>
                     @foreach($alunos as $aluno)
                     <option value="{{ $aluno->id }}" {{ $transaction->user_id == $aluno->id ? 'selected' : '' }}>{{ $aluno->name }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="amount">Valor *</label>
                  <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $transaction->amount }}" required>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="discount">Desconto</label>
                  <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="{{ $transaction->discount }}">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="addition">Acréscimo/Juros</label>
                  <input type="number" step="0.01" class="form-control" id="addition" name="addition" value="{{ $transaction->addition }}">
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="due_date">Data de Vencimento</label>
                  <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $transaction->due_date ? $transaction->due_date->format('Y-m-d') : '' }}">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="payment_method">Forma de Pagamento</label>
                  <select class="form-control" id="payment_method" name="payment_method">
                     <option value="">Selecione</option>
                     <option value="PIX" {{ $transaction->payment_method == 'PIX' ? 'selected' : '' }}>PIX</option>
                     <option value="Cartão de Crédito" {{ $transaction->payment_method == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                     <option value="Cartão de Débito" {{ $transaction->payment_method == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                     <option value="Dinheiro" {{ $transaction->payment_method == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                     <option value="Boleto" {{ $transaction->payment_method == 'Boleto' ? 'selected' : '' }}>Boleto</option>
                     <option value="Transferência" {{ $transaction->payment_method == 'Transferência' ? 'selected' : '' }}>Transferência</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="status">Status *</label>
                  <select class="form-control" id="status" name="status" required>
                     <option value="pendente" {{ $transaction->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                     <option value="pago" {{ $transaction->status == 'pago' ? 'selected' : '' }}>Pago</option>
                     <option value="vencido" {{ $transaction->status == 'vencido' ? 'selected' : '' }}>Vencido</option>
                     <option value="cancelado" {{ $transaction->status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                  </select>
               </div>
            </div>
         </div>

         <div class="row" id="paidFields" style="display: {{ $transaction->status == 'pago' ? 'block' : 'none' }};">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="paid_at">Data de Pagamento</label>
                  <input type="date" class="form-control" id="paid_at" name="paid_at" value="{{ $transaction->paid_at ? $transaction->paid_at->format('Y-m-d') : '' }}">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="amount_paid">Valor Pago</label>
                  <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid" value="{{ $transaction->amount_paid }}">
               </div>
            </div>
         </div>

         <div class="form-group">
            <a href="{{ $transaction->kind === 'conta_receber' ? route('financeiro.contas-receber.index') : route('financeiro.contas-pagar.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Atualizar</button>
         </div>
      </form>
   </div>
</div>

@push('js')
<script>
   $('#status').change(function() {
      if ($(this).val() === 'pago') {
         $('#paidFields').show();
      } else {
         $('#paidFields').hide();
      }
   });
</script>
@endpush
@stop
