@extends('adminlte::page')

@section('title', 'Criar Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Criar Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form method="POST" action="{{ route('treino.store') }}">
         @csrf

         @if($planilha)
         @php
            $planilhaDescricao = $planilha->is_padrao
               ? 'Padrão ' . ($planilha->nome ?: 'sem nome')
               : ($planilha->aluno?->name ? 'Aluno ' . $planilha->aluno->name : 'Aluno não vinculado');
         @endphp
         <input type="hidden" name="planilha_id" value="{{ $planilha->id }}">
         <div class="alert alert-info">
            <strong>Planilha:</strong> {{ $planilhaDescricao }} - Criada em {{ $planilha->created_at->format('d/m/Y') }}
         </div>
         @else
         <div class="form-group">
            <label for="planilha_id">Planilha de Treino *</label>
            <select name="planilha_id" id="planilha_id" class="form-control" required>
               <option value="">Selecione a planilha</option>
               @foreach(\App\Models\PlanilhaTreino::with('aluno')->get() as $p)
               @php
                  $planilhaDescricao = $p->is_padrao
                     ? 'Padrão: ' . ($p->nome ?: 'sem nome')
                     : ($p->aluno?->name ?: 'Aluno não vinculado');
               @endphp
               <option value="{{ $p->id }}" {{ old('planilha_id') == $p->id ? 'selected' : '' }}>
                  {{ $planilhaDescricao }} - {{ $p->created_at->format('d/m/Y') }}
               </option>
               @endforeach
            </select>
            @error('planilha_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
         @endif

         <div class="form-group">
            <label for="sigla">Sigla do Treino * (ex: A, B, C, Peito, Costas)</label>
            <input type="text" name="sigla" id="sigla" class="form-control" value="{{ old('sigla') }}" maxlength="10" required>
            @error('sigla')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="nome">Nome do Treino (opcional)</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" maxlength="255">
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="dias_semana">Dias da Semana (ex: Segunda, Quarta, Sexta)</label>
            <input type="text" name="dias_semana" id="dias_semana" class="form-control" value="{{ old('dias_semana') }}" maxlength="255">
            @error('dias_semana')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes') }}</textarea>
            @error('observacoes')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">Criar Treino</button>
         @if($planilha)
         <a href="{{ route('planilha-treino.show', $planilha->id) }}" class="btn btn-secondary">Cancelar</a>
         @else
         <a href="{{ route('planilha-treino.index') }}" class="btn btn-secondary">Cancelar</a>
         @endif
      </form>
   </div>
</div>
@stop
