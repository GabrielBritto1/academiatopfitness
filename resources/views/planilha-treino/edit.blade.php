@extends('adminlte::page')

@section('title', 'Planilha Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Editar Planilha Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('planilha-treino.update', $planilha->id) }}">
         @csrf
         @method('PUT')
         <div class="form-group">
            <div class="row">
               <div class="col">
                  <label for="unidade_id">Unidade</label>
                  <input type="text" name="unidade_id" class="form-control" disabled value="{{ $planilha->unidade->nome }}" required>
               </div>
               <div class="col">
                  <label for="professor_id">Professor</label>
                  <input type="text" name="professor_id" class="form-control" disabled value="{{ $planilha->professor->name }}" required>
               </div>
            </div>
         </div>
         <div class="form-group">
            <div class="row">
               <div class="col">
                  <label for="aluno_id">Aluno</label>
                  <input type="text" name="aluno_id" class="form-control" disabled value="{{ $planilha->aluno->name }}" required>
               </div>
               <div class="col">
                  <label for="plano_id">Plano</label>
                  <input type="text" name="plano_id" class="form-control" disabled value="{{ $planilha->plano->name }}" required>
               </div>
            </div>
         </div>
         <button type="submit" class="btn btn-warning text-bold">Salvar</button>
      </form>
   </div>
</div>
@stop