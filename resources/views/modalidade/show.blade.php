@extends('adminlte::page')

@section('title', 'Visualizar Modalidade')

@section('content_header')
<h1 class="text-bold">Visualizar Modalidade</h1>
@stop

@section('content')
<div class="card-body">
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $modalidadeShow->name }}" disabled required>
    </div>
    <div class="form-group">
        <label for="text">Descrição</label>
        <input type="text" name="text" class="form-control" value="{{ $modalidadeShow->descricao }}" disabled required>
    </div>
    <div class="form-group">
        <label for="text">Duração</label>
        <input type="text" name="text" class="form-control" value="{{ $modalidadeShow->duracao }}" disabled required>
    </div>
    <div class="form-group">
        <label for="text">Nível de dificuldade</label>
        <input type="text" name="text" class="form-control" value="{{ $modalidadeShow->nivel_dificuldade }}" disabled required>
    </div>
    <div class="form-group">
        <label for="text">Status</label>
        @if ($modalidadeShow->status == true)
            <input type="text" name="text" class="form-control" value="Ativo" disabled required>
        @else
            <input type="text" name="text" class="form-control" value="Inativo" disabled required>
        @endif
    </div>

    <a href="{{ route('modalidade.index') }}" class="btn btn-secondary">Voltar</a>
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