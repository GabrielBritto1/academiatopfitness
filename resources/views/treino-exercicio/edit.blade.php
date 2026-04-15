@extends('adminlte::page')

@section('title', 'Editar Exercício')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Editar Exercício</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('treino-exercicio.update', $exercicio->id) }}">
         @csrf
         @method('PUT')

         @php
            $planilhaDescricao = $exercicio->treino->planilha->is_padrao
               ? 'Padrão ' . ($exercicio->treino->planilha->nome ?: 'sem nome')
               : ($exercicio->treino->planilha->aluno?->name ?: 'Aluno não vinculado');
         @endphp
         <div class="alert alert-info">
            <strong>Treino:</strong> {{ $exercicio->treino->sigla }}
            @if($exercicio->treino->nome)
            - {{ $exercicio->treino->nome }}
            @endif
            (Planilha: {{ $planilhaDescricao }})
         </div>

         <div class="form-group">
            <label for="nome">Nome do Exercício *</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $exercicio->nome) }}" maxlength="255" required>
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                  <label for="series">Séries</label>
                  <input type="text" name="series" id="series" class="form-control" value="{{ old('series', $exercicio->series) }}" maxlength="50">
                  @error('series')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="repeticoes">Repetições</label>
                  <input type="text" name="repeticoes" id="repeticoes" class="form-control" value="{{ old('repeticoes', $exercicio->repeticoes) }}" maxlength="50">
                  @error('repeticoes')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="carga">Carga</label>
                  <input type="text" name="carga" id="carga" class="form-control" value="{{ old('carga', $exercicio->carga) }}" maxlength="50">
                  @error('carga')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="descanso">Descanso</label>
                  <input type="text" name="descanso" id="descanso" class="form-control" value="{{ old('descanso', $exercicio->descanso) }}" maxlength="50" placeholder="ex: 60s">
                  @error('descanso')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
         </div>

         <div class="form-group">
            <label for="ordem">Ordem</label>
            <input type="number" name="ordem" id="ordem" class="form-control" value="{{ old('ordem', $exercicio->ordem) }}" min="0">
            @error('ordem')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="observacao">Observação</label>
            <textarea name="observacao" id="observacao" class="form-control" rows="2">{{ old('observacao', $exercicio->observacao) }}</textarea>
            @error('observacao')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">Salvar Alterações</button>
         <a href="{{ route('treino.show', $exercicio->treino_id) }}" class="btn btn-secondary">Cancelar</a>
      </form>
   </div>
</div>
@stop
