@extends('adminlte::page')

@section('title', 'Comparar Avaliações')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-balance-scale"></i> Comparar Avaliações - {{ $aluno->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      @if($avaliacoes->count() < 2)
      <div class="alert alert-warning">
         <i class="fas fa-exclamation-triangle"></i> 
         É necessário ter pelo menos 2 avaliações para fazer uma comparação.
      </div>
      <a href="{{ route('avaliacao.show', $aluno->id) }}" class="btn btn-secondary">
         <i class="fas fa-arrow-left"></i> Voltar
      </a>
      @else
      <form method="POST" action="{{ route('avaliacao.comparacao_pdf', $aluno->id) }}">
         @csrf
         
         <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Selecione pelo menos 2 avaliações</strong> para gerar a tabela de comparação em PDF.
         </div>

         <div class="form-group">
            <label class="font-weight-bold">Selecione as avaliações para comparar:</label>
            <div class="row mt-3">
               @foreach($avaliacoes as $avaliacao)
               <div class="col-md-6 mb-3">
                  <div class="card">
                     <div class="card-body">
                        <div class="form-check">
                           <input class="form-check-input" 
                                  type="checkbox" 
                                  name="avaliacoes[]" 
                                  value="{{ $avaliacao->id }}" 
                                  id="avaliacao_{{ $avaliacao->id }}"
                                  @if($loop->index < 2) checked @endif>
                           <label class="form-check-label" for="avaliacao_{{ $avaliacao->id }}">
                              <strong>{{ $avaliacao->created_at->format('d/m/Y H:i') }}</strong>
                              <br>
                              <small class="text-muted">
                                 Professor: {{ $avaliacao->professor->name ?? '—' }}
                                 @if($avaliacao->peso)
                                 | Peso: {{ $avaliacao->peso }}kg
                                 @endif
                                 @if($avaliacao->imc)
                                 | IMC: {{ $avaliacao->imc }}
                                 @endif
                              </small>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
         </div>

         <div class="form-group">
            <button type="submit" class="btn btn-warning text-bold" id="btnGerar">
               <i class="fas fa-file-pdf"></i> Gerar PDF de Comparação
            </button>
            <a href="{{ route('avaliacao.show', $aluno->id) }}" class="btn btn-secondary">
               <i class="fas fa-arrow-left"></i> Voltar
            </a>
         </div>
      </form>
      @endif
   </div>
</div>
@stop

@section('js')
<script>
   $(document).ready(function() {
      const checkboxes = $('input[name="avaliacoes[]"]');
      const btnGerar = $('#btnGerar');

      function updateButton() {
         const checked = checkboxes.filter(':checked').length;
         if (checked < 2) {
            btnGerar.prop('disabled', true);
            btnGerar.html('<i class="fas fa-exclamation-triangle"></i> Selecione pelo menos 2 avaliações');
         } else {
            btnGerar.prop('disabled', false);
            btnGerar.html('<i class="fas fa-file-pdf"></i> Gerar PDF de Comparação');
         }
      }

      checkboxes.on('change', updateButton);
      updateButton();
   });
</script>
@stop

