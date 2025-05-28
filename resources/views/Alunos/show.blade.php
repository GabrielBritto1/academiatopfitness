@extends('adminlte::page')

@section('title', 'Visualizar Aluno')

@section('content_header')
<h1>Visualizar Aluno</h1>
@stop

@section('content')
<div class="card-body">
   <div class="form-group">
      <label for="name">Nome</label>
      <input type="text" name="name" class="form-control" value="{{ $aluno->name }}" required>
   </div>

   <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" name="email" class="form-control" value="{{ $aluno->email }}" required>
   </div>

   <a href="{{ route('aluno.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
@stop