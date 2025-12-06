@extends('adminlte::page')

@section('title', 'Editar Aluno')

@section('content_header')
<h1 class="text-bold">Editar Aluno</h1>
@stop

@section('content')

<form method="POST" action="{{ route('aluno.update', $aluno->id) }}" enctype="multipart/form-data">
   @csrf
   @method('PUT')

   <div class="card shadow-sm">

      <div class="card-body">

         {{-- NOME --}}
         <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $aluno->name }}" required>
         </div>

         {{-- CPF --}}
         <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" name="cpf" class="form-control" value="{{ $aluno->cpf }}" placeholder="000.000.000-00">
         </div>

         {{-- EMAIL --}}
         <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ $aluno->email }}" required>
         </div>

         <div class="row">
            {{-- TELEFONE --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" name="telefone" class="form-control" value="{{ $aluno->telefone }}">
               </div>
            </div>

            {{-- IDADE --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="idade">Idade</label>
                  <input type="number" name="idade" class="form-control" value="{{ $aluno->idade }}">
               </div>
            </div>

            {{-- SEXO --}}
            <div class="col-md-4">
               <div class="form-group">
                  <label for="sexo">Sexo</label>
                  <select name="sexo" class="form-control">
                     <option value="">Selecione</option>
                     <option value="Masculino" {{ $aluno->sexo === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                     <option value="Feminino" {{ $aluno->sexo === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                  </select>
               </div>
            </div>
         </div>

         {{-- UNIDADE --}}
         <div class="form-group">
            <label for="unidade_id">Unidade</label>
            <select name="unidade_id" class="form-control">
               <option value="">Selecione</option>
               @foreach($unidades as $unidade)
               <option value="{{ $unidade->id }}"
                  {{ $aluno->unidade_id == $unidade->id ? 'selected' : '' }}>
                  {{ $unidade->name }}
               </option>
               @endforeach
            </select>
         </div>

         {{-- STATUS --}}
         <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control">
               <option value="ativo" {{ $aluno->status === 'ativo' ? 'selected' : '' }}>Ativo</option>
               <option value="inativo" {{ $aluno->status === 'inativo' ? 'selected' : '' }}>Inativo</option>
            </select>
         </div>

         {{-- OBSERVAÇÕES --}}
         <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3">{{ $aluno->observacoes }}</textarea>
         </div>

         {{-- FOTO DO ALUNO --}}
         <div class="form-group">
            <label for="foto">Foto do Aluno</label>
            <input type="file" name="foto" class="form-control-file">

            @if($aluno->foto)
            <div class="mt-2">
               <img src="{{ asset('storage/alunos/' . $aluno->foto) }}"
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