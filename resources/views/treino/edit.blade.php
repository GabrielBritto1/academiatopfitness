@extends('adminlte::page')

@section('title', 'Editar Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Editar Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('treino.update', $treino->id) }}">
         @csrf
         @method('PUT')

         @php
            $planilhaDescricao = $treino->planilha->is_padrao
               ? 'Padrão ' . ($treino->planilha->nome ?: 'sem nome')
               : ($treino->planilha->aluno?->name ? 'Aluno ' . $treino->planilha->aluno->name : 'Aluno não vinculado');
         @endphp
         <div class="alert alert-info">
            <strong>Planilha:</strong> {{ $planilhaDescricao }} - Criada em {{ $treino->planilha->created_at->format('d/m/Y') }}
         </div>

         <div class="form-group">
            <label for="sigla">Sigla do Treino * (ex: A, B, C, Peito, Costas)</label>
            <input type="text" name="sigla" id="sigla" class="form-control" value="{{ old('sigla', $treino->sigla) }}" maxlength="10" required>
            @error('sigla')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="nome">Nome do Treino (opcional)</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome', $treino->nome) }}" maxlength="255">
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="dias_semana">Dias da Semana (ex: Segunda, Quarta, Sexta)</label>
            <input type="text" name="dias_semana" id="dias_semana" class="form-control" value="{{ old('dias_semana', $treino->dias_semana) }}" maxlength="255">
            @error('dias_semana')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes', $treino->observacoes) }}</textarea>
            @error('observacoes')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">Salvar Alterações</button>
         <a href="{{ route('treino.show', $treino->id) }}" class="btn btn-secondary">Cancelar</a>
      </form>
   </div>
</div>
@stop
