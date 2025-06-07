@extends('adminlte::page')

@section('title', 'Editar Modalidade')

@section('content_header')
<h1><i class="fas fa fa-tags"></i> Editar Modalidade</h1>
@stop

@section('content')
<form method="POST" action="{{ route('modalidade.update',$modalidadeEdit->id) }}">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $modalidadeEdit->name }}" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <input type="descricao" name="descricao" class="form-control" value="{{ $modalidadeEdit->descricao }}" required>
        </div>

        <div class="form-group">
            <label for="duracao">Duração</label>
            <input type="duracao" name="duracao" class="form-control" value="{{ $modalidadeEdit->duracao }}" required>
        </div>

        <div class="form-group">
            <label for="nivel_dificuldade">Nível de Dificuldade</label>
            <input type="nivel_dificuldade" name="nivel_dificuldade" class="form-control" value="{{ $modalidadeEdit->nivel_dificuldade }}" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="1" {{ $modalidadeEdit->status == 1 ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ $modalidadeEdit->status == 0 ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Editar Modalidade</button>
    </div>
</form>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
{{-- Add here extra javascripts --}}
@stop