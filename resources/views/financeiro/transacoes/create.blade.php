@extends('adminlte::page')

@section('title', $kind === 'conta_receber' ? 'Nova Conta a Receber' : 'Nova Conta a Pagar')

@section('content_header')
<h1 class="text-bold">
   <i class="fas fa-{{ $kind === 'conta_receber' ? 'hand-holding-usd' : 'money-bill-wave' }}"></i>
   {{ $kind === 'conta_receber' ? 'Nova Conta a Receber' : 'Nova Conta a Pagar' }}
</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('financeiro.transacoes.store') }}" method="POST">
         @csrf
         <input type="hidden" name="kind" value="{{ $kind }}">

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="description">Descrição *</label>
                  <input type="text" class="form-control" id="description" name="description" required>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="financial_category_id">Categoria</label>
                  <select class="form-control" id="financial_category_id" name="financial_category_id">
                     <option value="">Selecione uma categoria</option>
                     @foreach($categories as $category)
                     <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                     <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="user_id">{{ $kind === 'conta_receber' ? 'Aluno' : 'Fornecedor' }}</label>
                  <select class="form-control" id="user_id" name="user_id">
                     <option value="">Selecione</option>
                     @foreach($alunos as $aluno)
                     <option value="{{ $aluno->id }}">{{ $aluno->name }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="amount">Valor *</label>
                  <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="discount">Desconto</label>
                  <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="0">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="addition">Acréscimo/Juros</label>
                  <input type="number" step="0.01" class="form-control" id="addition" name="addition" value="0">
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="due_date">Data de Vencimento</label>
                  <input type="date" class="form-control" id="due_date" name="due_date">
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="payment_method">Forma de Pagamento</label>
                  <select class="form-control" id="payment_method" name="payment_method">
                     <option value="">Selecione</option>
                     <option value="PIX">PIX</option>
                     <option value="Cartão de Crédito">Cartão de Crédito</option>
                     <option value="Cartão de Débito">Cartão de Débito</option>
                     <option value="Dinheiro">Dinheiro</option>
                     <option value="Boleto">Boleto</option>
                     <option value="Transferência">Transferência</option>
                  </select>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="status">Status *</label>
                  <select class="form-control" id="status" name="status" required>
                     <option value="pendente">Pendente</option>
                     <option value="pago">Pago</option>
                     <option value="vencido">Vencido</option>
                     <option value="cancelado">Cancelado</option>
                  </select>
               </div>
            </div>
         </div>

         <div class="row" id="paidFields" style="display: none;">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="paid_at">Data de Pagamento</label>
                  <input type="date" class="form-control" id="paid_at" name="paid_at">
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="amount_paid">Valor Pago</label>
                  <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid">
               </div>
            </div>
         </div>

         <div class="form-group">
            <a href="{{ $kind === 'conta_receber' ? route('financeiro.contas-receber.index') : route('financeiro.contas-pagar.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
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
