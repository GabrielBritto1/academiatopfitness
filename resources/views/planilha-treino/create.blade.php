@extends('adminlte::page')

@section('title', 'Criar Planilha de Treino')
@section('content_header')
<h1 class="text-bold">
   <i class="fas fa-dumbbell"></i> 
   @if(request('is_padrao'))
   Criar Planilha Padrão
   @else
   Criar Planilha de Treino
   @endif
</h1>
@stop

@section('content')

@php
   $isPadrao = request('is_padrao', old('is_padrao', false));
@endphp

@if($aluno && !$isPadrao && $planilhasPadrao->count() > 0)
{{-- OPÇÃO DE USAR PLANILHA PADRÃO --}}
<div class="card mb-3">
   <div class="card-header bg-primary">
      <h4 class="mb-0 text-white"><i class="fas fa-star"></i> Usar Planilha Padrão</h4>
   </div>
   <div class="card-body">
      <p class="text-muted">Selecione uma planilha padrão para aplicar ao aluno <strong>{{ $aluno->name }}</strong>:</p>
      <div class="row">
         @foreach($planilhasPadrao as $planilhaPadrao)
         <div class="col-md-6 mb-3">
            <div class="card border-primary">
               <div class="card-body">
                  <h5 class="card-title">
                     {{ $planilhaPadrao->nome ?? 'Sem nome' }}
                     <span class="badge badge-primary">Padrão</span>
                  </h5>
                  <p class="card-text">
                     <small>
                        <strong>Treinos:</strong> {{ $planilhaPadrao->treinos->count() }}<br>
                        <strong>Unidade:</strong> {{ $planilhaPadrao->unidade->nome ?? '—' }}<br>
                        <strong>Professor:</strong> {{ $planilhaPadrao->professor->name ?? '—' }}
                     </small>
                  </p>
                  <form method="POST" action="{{ route('planilha-treino.store') }}" class="d-inline">
                     @csrf
                     <input type="hidden" name="planilha_padrao_id" value="{{ $planilhaPadrao->id }}">
                     <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
                     <div class="form-group mb-2">
                        <label for="professor_id_{{ $planilhaPadrao->id }}">Professor *</label>
                        <select name="professor_id" id="professor_id_{{ $planilhaPadrao->id }}" class="form-control form-control-sm" required>
                           <option value="">Selecione</option>
                           @foreach($professores as $professor)
                           <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group mb-2">
                        <label for="unidade_id_{{ $planilhaPadrao->id }}">Unidade *</label>
                        <select name="unidade_id" id="unidade_id_{{ $planilhaPadrao->id }}" class="form-control form-control-sm" required>
                           <option value="">Selecione</option>
                           @foreach($unidades as $unidade)
                           <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="form-group mb-2">
                        <label for="plano_id_{{ $planilhaPadrao->id }}">Plano</label>
                        <select name="plano_id" id="plano_id_{{ $planilhaPadrao->id }}" class="form-control form-control-sm">
                           <option value="">Nenhum</option>
                           @foreach($planos as $plano)
                           <option value="{{ $plano->id }}">{{ $plano->name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-check"></i> Aplicar ao Aluno
                     </button>
                  </form>
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</div>

<div class="text-center mb-3">
   <hr>
   <strong class="text-muted">OU</strong>
   <hr>
</div>
@endif

{{-- CRIAR NOVA PLANILHA --}}
<div class="card">
   <div class="card-header">
      <h4 class="mb-0">
         @if($isPadrao)
         <i class="fas fa-star"></i> Criar Nova Planilha Padrão
         @else
         <i class="fas fa-plus"></i> Criar Nova Planilha Personalizada
         @endif
      </h4>
   </div>
   <div class="card-body">
      <form method="POST" action="{{ route('planilha-treino.store') }}">
         @csrf

         @if($isPadrao)
         <input type="hidden" name="is_padrao" value="1">
         <div class="form-group">
            <label for="nome">Nome da Planilha Padrão *</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ old('nome') }}" placeholder="ex: Iniciante, Intermediário, Avançado" required>
            @error('nome')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
         @else
         @if($aluno)
         <input type="hidden" name="aluno_id" value="{{ $aluno->id }}">
         <div class="alert alert-info">
            <strong>Aluno:</strong> {{ $aluno->name }}
         </div>
         @else
         <div class="form-group">
            <label for="aluno_id">Aluno *</label>
            <select name="aluno_id" id="aluno_id" class="form-control" required>
               <option value="">Selecione o aluno</option>
               @foreach(\App\Models\User::role('aluno')->get() as $user)
               <option value="{{ $user->id }}" {{ old('aluno_id') == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
               </option>
               @endforeach
            </select>
            @error('aluno_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>
         @endif
         @endif

         <div class="form-group">
            <label for="professor_id">Professor *</label>
            <select name="professor_id" id="professor_id" class="form-control" required>
               <option value="">Selecione o professor</option>
               @foreach($professores as $professor)
               <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
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
               <option value="">Selecione a unidade</option>
               @foreach($unidades as $unidade)
               <option value="{{ $unidade->id }}" {{ old('unidade_id') == $unidade->id ? 'selected' : '' }}>
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
               <option value="">Selecione o plano (opcional)</option>
               @foreach($planos as $plano)
               <option value="{{ $plano->id }}" {{ old('plano_id') == $plano->id ? 'selected' : '' }}>
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
            <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes') }}</textarea>
            @error('observacoes')
            <span class="text-danger">{{ $message }}</span>
            @enderror
         </div>

         <button type="submit" class="btn btn-warning text-bold">
            @if($isPadrao)
            Criar Planilha Padrão
            @else
            Criar Planilha
            @endif
         </button>
         @if($aluno && !$isPadrao)
         <a href="{{ route('aluno.show', $aluno->id) }}" class="btn btn-secondary">Cancelar</a>
         @else
         <a href="{{ route('planilha-treino.index') }}" class="btn btn-secondary">Cancelar</a>
         @endif
      </form>
   </div>
</div>
@stop
