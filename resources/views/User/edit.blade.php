@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
<h1>Editar Usuário</h1>
@stop

@section('content')
<form method="POST" action="{{ route('user.update',$userEdit->id) }}">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $userEdit->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ $userEdit->email }}" required>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Editar Usuário</button>
    </div>
</form>
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