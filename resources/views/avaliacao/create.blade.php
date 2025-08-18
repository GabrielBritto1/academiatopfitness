@extends('adminlte::page')
@section('title', 'Avaliações')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-clipboard-list"></i> Fazer avaliação do aluno: </h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('avaliacao.store') }}" method="POST">
         @csrf
         <!-- <div class="form-group mt-3">
            <label for="aluno_id">Aluno</label>
            <select class="form-control form-control-lg" name="aluno_id" id="aluno_id" required>
               <option value="" selected disabled>Selecione um Aluno</option>
               @foreach($alunos as $aluno)
               <option value="{{ $aluno->id }}">{{ $aluno->name }}</option>
               @endforeach
            </select>
         </div> -->
         <input type="hidden" name="aluno_id" value="{{ request()->get('aluno_id') }}">
         <input type="hidden" name="professor_id" value="{{ request()->get('professor_id') }}">
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="peso">Peso</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="peso" id="peso">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="altura">Altura</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="altura" id="altura">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="imc">IMC</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="imc" id="imc">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="gordura">Gordura (%)</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="gordura" id="gordura">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="massa_muscular">Massa Muscular</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="massa_muscular" id="massa_muscular">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_cintura">Circunferência Cintura</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_cintura" id="circunferencia_cintura">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_quadril">Circunferência Quadril</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_quadril" id="circunferencia_quadril">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_braco_relaxado">Braço Relaxado</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_braco_relaxado" id="circunferencia_braco_relaxado">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_braco_contraido">Braço Contraído</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_braco_contraido" id="circunferencia_braco_contraido">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_peito">Peito</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_peito" id="circunferencia_peito">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_coxa">Coxa</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_coxa" id="circunferencia_coxa">
               </div>
            </div>
            <div class="col">
               <div class="form-group mt-3">
                  <label for="circunferencia_panturrilha">Panturrilha</label>
                  <input type="text" class="form-control form-control-lg" maxlength="5" name="circunferencia_panturrilha" id="circunferencia_panturrilha">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <div class="form-group mt-3">
                  <label for="observacoes">Observações</label>
                  <textarea class="form-control form-control-lg" name="observacoes" id="observacoes"></textarea>
               </div>
            </div>
         </div>
         <button type="submit" class="btn btn-warning mt-3 text-bold">Salvar Avaliação</button>
      </form>
   </div>
</div>
@stop

@section('js')
<script>
   $(function() {
      function calcularIMC() {
         const peso = parseFloat($('#peso').val()) || 0;
         const altura = parseFloat($('#altura').val()) || 0;
         let imc = '';
         if (peso > 0 && altura > 0) {
            imc = (peso / (altura * altura)).toFixed(2);
         }
         $('#imc').val(imc);
      }

      $('#peso, #altura').on('input', calcularIMC);
   });
</script>
@stop