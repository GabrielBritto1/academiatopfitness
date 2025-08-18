@extends('adminlte::page')
@section('title', 'Relatório Financeiro')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-pdf"></i> Filtro para Relatório Financeiro</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <div class="row">
         <div class="col-md-6">
            <form action="{{ route('relatorio.relatorio_pdf.relatorio_financeiro') }}" method="GET">
               <div class="form-group">
                  <label for="status">Status</label>
                  <select name="status" id="status" class="form-control">
                     <option value="" selected>Todos</option>
                     <option value="1">Ativo</option>
                     <option value="0">Inativo</option>
                  </select>
               </div>
               <button class="btn btn-warning" type="submit"><i class="fas fa-file-pdf"></i> Gerar Relatório</button>
            </form>
         </div>
      </div>
   </div>
</div>
@stop