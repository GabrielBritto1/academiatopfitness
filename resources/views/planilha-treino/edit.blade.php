@extends('adminlte::page')

@section('title', 'Editar Planilha de Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Editar Planilha de Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('planilha-treino.update', $planilha->id) }}">
         @csrf
         @method('PUT')

         @if($planilha->is_padrao)
         <div class="alert alert-info">
            <strong>Planilha Padrão:</strong> {{ $planilha->nome ?? 'Sem nome' }}
         </div>
         <div class="form-group">
            <label for="nome">Nome da Planilha Padrão *</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $planilha->nome) }}" required>
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
         @else
         <input type="hidden" name="aluno_id" value="{{ $planilha->aluno_id }}">

         <div class="form-group">
            <label for="aluno_id">Aluno</label>
            <input type="text" class="form-control" value="{{ $planilha->aluno->name ?? '—' }}" disabled>
         </div>
         @endif

         <div class="form-group">
            <label for="professor_id">Professor *</label>
            <select name="professor_id" id="professor_id" class="form-control" required>
               @foreach($professores as $professor)
               <option value="{{ $professor->id }}" {{ $planilha->professor_id == $professor->id ? 'selected' : '' }}>
                  {{ $professor->name }}
               </option>
               @endforeach
            </select>
            @error('professor_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="unidade_id">Unidade *</label>
            <select name="unidade_id" id="unidade_id" class="form-control" required>
               @foreach($unidades as $unidade)
               <option value="{{ $unidade->id }}" {{ $planilha->unidade_id == $unidade->id ? 'selected' : '' }}>
                  {{ $unidade->nome }}
               </option>
               @endforeach
            </select>
            @error('unidade_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="plano_id">Plano</label>
            <select name="plano_id" id="plano_id" class="form-control">
               <option value="">Nenhum plano</option>
               @foreach($planos as $plano)
               <option value="{{ $plano->id }}" {{ $planilha->plano_id == $plano->id ? 'selected' : '' }}>
                  {{ $plano->name }}
               </option>
               @endforeach
            </select>
            @error('plano_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes', $planilha->observacoes) }}</textarea>
            @error('observacoes')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">Salvar</button>
         <a href="{{ route('planilha-treino.show', $planilha->id) }}" class="btn btn-secondary">Cancelar</a>
      </form>
   </div>
</div>
@stop