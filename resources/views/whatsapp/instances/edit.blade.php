@extends('adminlte::page')

@section('title', 'Editar Instância do WhatsApp')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
   <h1 class="text-bold"><i class="fab fa-whatsapp"></i> Editar Instância</h1>
   <a href="{{ route('whatsapp.instances.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <h3 class="card-title">{{ $instance->name }}</h3>
   </div>
   <form method="POST" action="{{ route('whatsapp.instances.update', $instance->id) }}">
      @method('PUT')
      @include('whatsapp.instances.form', ['instance' => $instance])
      <div class="card-footer">
         <button type="submit" class="btn btn-primary">Salvar alterações</button>
      </div>
   </form>
</div>
@stop
