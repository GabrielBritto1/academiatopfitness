@extends('adminlte::page')

@section('title', 'Filtrar Avaliações para PDF')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-pdf"></i> Filtrar Avaliações - {{ $aluno->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="GET" action="{{ route('avaliacao.avaliacao_pdf', $aluno->id) }}">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="dia">Dia</label>
                  <select name="dia" id="dia" class="form-control">
                     <option value="">Todos os dias</option>
                     @for($i = 1; $i <= 31; $i++)
                     <option value="{{ $i }}" {{ old('dia') == $i ? 'selected' : '' }}>
                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                     </option>
                     @endfor
                  </select>
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="mes">Mês</label>
                  <select name="mes" id="mes" class="form-control">
                     <option value="">Todos os meses</option>
                     <option value="1" {{ old('mes') == 1 ? 'selected' : '' }}>Janeiro</option>
                     <option value="2" {{ old('mes') == 2 ? 'selected' : '' }}>Fevereiro</option>
                     <option value="3" {{ old('mes') == 3 ? 'selected' : '' }}>Março</option>
                     <option value="4" {{ old('mes') == 4 ? 'selected' : '' }}>Abril</option>
                     <option value="5" {{ old('mes') == 5 ? 'selected' : '' }}>Maio</option>
                     <option value="6" {{ old('mes') == 6 ? 'selected' : '' }}>Junho</option>
                     <option value="7" {{ old('mes') == 7 ? 'selected' : '' }}>Julho</option>
                     <option value="8" {{ old('mes') == 8 ? 'selected' : '' }}>Agosto</option>
                     <option value="9" {{ old('mes') == 9 ? 'selected' : '' }}>Setembro</option>
                     <option value="10" {{ old('mes') == 10 ? 'selected' : '' }}>Outubro</option>
                     <option value="11" {{ old('mes') == 11 ? 'selected' : '' }}>Novembro</option>
                     <option value="12" {{ old('mes') == 12 ? 'selected' : '' }}>Dezembro</option>
                  </select>
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-group">
                  <label for="ano">Ano</label>
                  <select name="ano" id="ano" class="form-control">
                     <option value="">Todos os anos</option>
                     @for($i = date('Y'); $i >= date('Y') - 10; $i--)
                     <option value="{{ $i }}" {{ old('ano', date('Y')) == $i ? 'selected' : '' }}>
                        {{ $i }}
                     </option>
                     @endfor
                  </select>
               </div>
            </div>
         </div>

         <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Dica:</strong> Você pode combinar os filtros. Deixe em branco para não filtrar por aquele campo.
            <br>Exemplo: Selecione apenas o mês para ver todas as avaliações daquele mês, ou combine mês e ano para um período específico.
         </div>

         <div class="form-group">
            <button type="submit" class="btn btn-warning text-bold">
               <i class="fas fa-file-pdf"></i> Gerar PDF com Filtros
            </button>
            <a href="{{ route('avaliacao.show', $aluno->id) }}" class="btn btn-secondary">
               <i class="fas fa-arrow-left"></i> Voltar
            </a>
         </div>
      </form>
   </div>
</div>
@stop

