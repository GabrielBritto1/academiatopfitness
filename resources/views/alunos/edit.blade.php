@extends('adminlte::page')

@section('title', 'Editar Aluno')

@section('content_header')
<h1 class="text-bold">Editar Aluno</h1>
@stop

@section('content')

@if($errors->any())
<div class="alert alert-danger">
   <strong>Não foi possível salvar o aluno.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<form method="POST" action="{{ route('aluno.update', $aluno->id) }}" enctype="multipart/form-data">
   @csrf
   @method('PUT')

   <div class="card shadow-sm">

      <div class="card-body">

         {{-- NOME --}}
         <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $aluno->name) }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- CPF --}}
         <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror" value="{{ old('cpf', $aluno->aluno?->cpf) }}" placeholder="000.000.000-00">
            @error('cpf')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- EMAIL --}}
         <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $aluno->email) }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         <div class="row">
            {{-- TELEFONE --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" name="telefone" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone', $aluno->aluno?->telefone) }}">
                  @error('telefone')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            {{-- DATA DE NASCIMENTO --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="data_nascimento">Data de Nascimento</label>
                  <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento', $aluno->aluno?->data_nascimento?->format('Y-m-d')) }}">
                  @error('data_nascimento')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>

            {{-- SEXO --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="sexo">Sexo</label>
                  <select name="sexo" class="form-control @error('sexo') is-invalid @enderror">
                     <option value="">Selecione</option>
                     <option value="Masculino" {{ old('sexo', $aluno->aluno?->sexo) === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                     <option value="Feminino" {{ old('sexo', $aluno->aluno?->sexo) === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                  </select>
                  @error('sexo')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
            </div>
         </div>

         {{-- UNIDADE --}}
         <div class="form-group">
            <label for="unidade_id">Unidade</label>
            <select name="unidade_id" class="form-control @error('unidade_id') is-invalid @enderror">
               <option value="">Selecione</option>
               @foreach($unidades as $unidade)
               <option value="{{ $unidade->id }}"
                  {{ old('unidade_id', $aluno->aluno?->unidade_id) == $unidade->id ? 'selected' : '' }}>
                  {{ $unidade->nome }}
               </option>
               @endforeach
            </select>
            @error('unidade_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- STATUS --}}
         <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control @error('status') is-invalid @enderror">
               <option value="1" {{ (string) old('status', (int) $aluno->status) === '1' ? 'selected' : '' }}>Ativo</option>
               <option value="0" {{ (string) old('status', (int) $aluno->status) === '0' ? 'selected' : '' }}>Inativo</option>
            </select>
            @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- OBSERVAÇÕES --}}
         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="3">{{ old('observacoes', $aluno->aluno?->observacoes) }}</textarea>
            @error('observacoes')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
         </div>

         {{-- FOTO DO ALUNO --}}
         <div class="form-group">
            <label for="foto">Foto do Aluno</label>
            <input type="file" name="foto" class="form-control-file @error('foto') is-invalid @enderror" accept="image/*">
            @error('foto')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @if($aluno->aluno?->foto)
            <div class="mt-2">
               <img src="{{ $aluno->aluno->foto_url }}"
                  class="img-thumbnail"
                  style="width: 120px; height:120px; object-fit: cover;">
            </div>
            @endif
         </div>

      </div>

      <div class="card-footer">
         <button type="submit" class="btn btn-primary">Salvar Alterações</button>
         <a href="{{ route('aluno.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>

   </div>

</form>

@stop
