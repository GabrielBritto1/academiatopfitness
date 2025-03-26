@extends('adminlte::page')

@section('title', 'Visualizar Usuário')

@section('content_header')
<h1>Visualizar Usuário</h1>
@stop

@section('content')
<div class="card-body">
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $userShow->name }}" required>
    </div>

    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ $userShow->email }}" required>
    </div>

    <a href="{{ route('user.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop