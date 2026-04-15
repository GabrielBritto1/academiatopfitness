@extends('adminlte::page')

@section('title', 'Adicionar Exercício')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Adicionar Exercício ao Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('treino-exercicio.store') }}">
         @csrf

         @if($treino)
         @php
            $planilhaDescricao = $treino->planilha->is_padrao
               ? 'Padrão ' . ($treino->planilha->nome ?: 'sem nome')
               : ($treino->planilha->aluno?->name ?: 'Aluno não vinculado');
         @endphp
         <input type="hidden" name="treino_id" value="{{ $treino->id }}">
         <div class="alert alert-info">
            <strong>Treino:</strong> {{ $treino->sigla }}
            @if($treino->nome)
            - {{ $treino->nome }}
            @endif
            (Planilha: {{ $planilhaDescricao }})
         </div>
         @else
         <div class="form-group">
            <label for="treino_id">Treino *</label>
            <select name="treino_id" id="treino_id" class="form-control" required>
               <option value="">Selecione o treino</option>
               @foreach(\App\Models\Treino::with('planilha.aluno')->get() as $t)
               @php
                  $planilhaDescricao = $t->planilha->is_padrao
                     ? 'Padrão: ' . ($t->planilha->nome ?: 'sem nome')
                     : ($t->planilha->aluno?->name ?: 'Aluno não vinculado');
               @endphp
               <option value="{{ $t->id }}" {{ old('treino_id') == $t->id ? 'selected' : '' }}>
                  {{ $t->sigla }} - {{ $planilhaDescricao }}
               </option>
               @endforeach
            </select>
            @error('treino_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
         @endif

         <div class="form-group">
            <label for="nome">Nome do Exercício *</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" maxlength="255" required>
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                  <label for="series">Séries</label>
                  <input type="text" name="series" id="series" class="form-control" value="{{ old('series') }}" maxlength="50">
                  @error('series')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="repeticoes">Repetições</label>
                  <input type="text" name="repeticoes" id="repeticoes" class="form-control" value="{{ old('repeticoes') }}" maxlength="50">
                  @error('repeticoes')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="carga">Carga</label>
                  <input type="text" name="carga" id="carga" class="form-control" value="{{ old('carga') }}" maxlength="50">
                  @error('carga')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>

            <div class="col-md-3">
               <div class="form-group">
                  <label for="descanso">Descanso</label>
                  <input type="text" name="descanso" id="descanso" class="form-control" value="{{ old('descanso') }}" maxlength="50" placeholder="ex: 60s">
                  @error('descanso')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
         </div>

         <div class="form-group">
            <label for="ordem">Ordem (opcional - será definida automaticamente se não informado)</label>
            <input type="number" name="ordem" id="ordem" class="form-control" value="{{ old('ordem') }}" min="0">
            @error('ordem')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="observacao">Observação</label>
            <textarea name="observacao" id="observacao" class="form-control" rows="2">{{ old('observacao') }}</textarea>
            @error('observacao')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">Adicionar Exercício</button>
         @if($treino)
         <a href="{{ route('treino.show', $treino->id) }}" class="btn btn-secondary">Cancelar</a>
         @else
         <a href="{{ route('planilha-treino.index') }}" class="btn btn-secondary">Cancelar</a>
         @endif
      </form>
   </div>
</div>
@stop
