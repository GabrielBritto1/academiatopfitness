@extends('adminlte::page')
@section('title', 'Relatório Financeiro')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-pdf"></i> Filtro para Relatório Financeiro</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="col-12">
            <form action="{{ route('relatorio.relatorio_pdf.relatorio_financeiro') }}" method="GET" target="_blank">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="date_from">Data inicial</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="date_to">Data final</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="academia_unidade_id">Unidade</label>
                        <select name="academia_unidade_id" id="academia_unidade_id" class="form-control">
                           <option value="">Todas</option>
                           @foreach($unidades as $unidade)
                           <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="kind">Tipo</label>
                        <select name="kind" id="kind" class="form-control">
                           <option value="">Todos</option>
                           <option value="conta_receber">Entradas (Receber)</option>
                           <option value="conta_pagar">Saídas (Pagar)</option>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                           <option value="">Todos</option>
                           <option value="pendente">A Receber / Pendente</option>
                           <option value="pago">Pago / Recebido</option>
                           <option value="vencido">Vencido</option>
                           <option value="cancelado">Cancelado</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="financial_category_id">Categoria</label>
                        <select name="financial_category_id" id="financial_category_id" class="form-control">
                           <option value="">Todas</option>
                           @foreach($categories as $cat)
                           <option value="{{ $cat->id }}">{{ strtoupper($cat->type) }} - {{ $cat->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="payment_method">Forma de pagamento</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                           <option value="">Todas</option>
                           @foreach($paymentMethods as $key => $label)
                           <option value="{{ $key }}">{{ $label }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3 d-flex align-items-end">
                     <button class="btn btn-warning w-100" type="submit">
                        <i class="fas fa-file-pdf"></i> Gerar PDF
                     </button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@stop