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
      @if(session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-danger">
         <strong>Não foi possível salvar a transação.</strong>
         <ul class="mb-0 mt-2 pl-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
      @endif

      <form action="{{ route('financeiro.transacoes.store') }}" method="POST">
         @csrf
         <input type="hidden" name="kind" value="{{ $kind }}">

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="description">Descrição *</label>
                  <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" required>
                  @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <div class="d-flex justify-content-between align-items-center">
                     <label for="financial_category_id">Categoria</label>
                     <a
                        href="{{ route('financeiro.categorias.index', ['type' => $kind === 'conta_receber' ? 'receita' : 'despesa', 'redirect_to' => request()->fullUrl()]) }}"
                        class="btn btn-xs btn-outline-primary"
                     >
                        Gerenciar categorias
                     </a>
                  </div>
                  <select class="form-control @error('financial_category_id') is-invalid @enderror" id="financial_category_id" name="financial_category_id">
                     <option value="">Selecione uma categoria</option>
                     @foreach($categories as $category)
                     <option value="{{ $category->id }}" {{ (string) old('financial_category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                     @endforeach
                  </select>
                  @error('financial_category_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  @if($categories->isEmpty())
                  <small class="text-danger d-block mt-2">Nenhuma categoria ativa encontrada para este tipo de lançamento.</small>
                  @endif
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="academia_unidade_id">Unidade</label>
                  <select class="form-control @error('academia_unidade_id') is-invalid @enderror" id="academia_unidade_id" name="academia_unidade_id">
                     <option value="">Selecione uma unidade</option>
                     @foreach($unidades as $unidade)
                     <option value="{{ $unidade->id }}" {{ (string) old('academia_unidade_id') === (string) $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
                  @error('academia_unidade_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="user_id">{{ $kind === 'conta_receber' ? 'Aluno' : 'Fornecedor' }}</label>
                  <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                     <option value="">Selecione</option>
                     @foreach($alunos as $aluno)
                     <option value="{{ $aluno->id }}" {{ (string) old('user_id') === (string) $aluno->id ? 'selected' : '' }}>{{ $aluno->name }}</option>
                     @endforeach
                  </select>
                  @error('user_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="amount">Valor *</label>
                  <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                  @error('amount')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="discount">Desconto</label>
                  <input type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{ old('discount', 0) }}">
                  @error('discount')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="addition">Acréscimo/Juros</label>
                  <input type="number" step="0.01" class="form-control @error('addition') is-invalid @enderror" id="addition" name="addition" value="{{ old('addition', 0) }}">
                  @error('addition')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="due_date">Data de Vencimento</label>
                  <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date') }}">
                  @error('due_date')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="payment_method">Forma de Pagamento</label>
                  <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                     <option value="">Selecione</option>
                     <option value="PIX" {{ old('payment_method') === 'PIX' ? 'selected' : '' }}>PIX</option>
                     <option value="Cartão de Crédito" {{ old('payment_method') === 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                     <option value="Cartão de Débito" {{ old('payment_method') === 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                     <option value="Dinheiro" {{ old('payment_method') === 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                     <option value="Boleto" {{ old('payment_method') === 'Boleto' ? 'selected' : '' }}>Boleto</option>
                     <option value="Transferência" {{ old('payment_method') === 'Transferência' ? 'selected' : '' }}>Transferência</option>
                  </select>
                  @error('payment_method')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="status">Status *</label>
                  <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                     <option value="pendente" {{ old('status', 'pendente') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                     <option value="pago" {{ old('status') === 'pago' ? 'selected' : '' }}>Pago</option>
                     <option value="vencido" {{ old('status') === 'vencido' ? 'selected' : '' }}>Vencido</option>
                     <option value="cancelado" {{ old('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                  </select>
                  @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         <div class="row" id="paidFields" style="display: {{ old('status') === 'pago' ? 'flex' : 'none' }};">
            <div class="col-md-6">
               <div class="form-group">
                  <label for="paid_at">Data de Pagamento</label>
                  <input type="date" class="form-control @error('paid_at') is-invalid @enderror" id="paid_at" name="paid_at" value="{{ old('paid_at') }}">
                  @error('paid_at')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
            <div class="col-md-6">
               <div class="form-group">
                  <label for="amount_paid">Valor Pago</label>
                  <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}">
                  @error('amount_paid')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
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
