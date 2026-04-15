@extends('adminlte::page')

@section('title', 'WhatsApp / Evolution API')

@section('content_header')
<h1 class="text-bold"><i class="fab fa-whatsapp"></i> Instâncias do WhatsApp</h1>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
   <strong>Não foi possível salvar a instância.</strong>
   <ul class="mb-0 mt-2 pl-3">
      @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif

<div class="row">
   <div class="col-lg-4">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Nova Instância</h3>
         </div>
         <form method="POST" action="{{ route('whatsapp.instances.store') }}">
            @csrf
            <div class="card-body">
               <div class="form-group">
                  <label for="name">Nome interno</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
               </div>
               <div class="form-group">
                  <label for="base_url">URL base da Evolution API</label>
                  <input type="url" id="base_url" name="base_url" class="form-control" value="{{ old('base_url') }}" placeholder="https://seu-servidor-evolution.com" required>
               </div>
               <div class="form-group">
                  <label for="instance_name">Nome da instância</label>
                  <input type="text" id="instance_name" name="instance_name" class="form-control" value="{{ old('instance_name') }}" required>
               </div>
               <div class="form-group">
                  <label for="api_key">API Key</label>
                  <textarea id="api_key" name="api_key" class="form-control" rows="3" required>{{ old('api_key') }}</textarea>
               </div>
               <div class="form-group">
                  <label for="description">Descrição</label>
                  <textarea id="description" name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
               </div>
               <div class="form-check mb-2">
                  <input type="hidden" name="is_active" value="0">
                  <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">Ativa</label>
               </div>
               <div class="form-check">
                  <input type="hidden" name="is_default" value="0">
                  <input type="checkbox" id="is_default" name="is_default" value="1" class="form-check-input" {{ old('is_default') ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_default">Definir como padrão</label>
               </div>
            </div>
            <div class="card-footer">
               <button type="submit" class="btn btn-success">Salvar Instância</button>
            </div>
         </form>
      </div>
   </div>

   <div class="col-lg-8">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">Instâncias cadastradas</h3>
         </div>
         <div class="card-body">
            @forelse($instances as $instance)
            <div class="border rounded p-3 mb-3">
               <div class="d-flex justify-content-between align-items-start flex-wrap">
                  <div>
                     <h5 class="mb-1">{{ $instance->name }}</h5>
                     <div class="text-muted mb-2">{{ $instance->base_url }}</div>
                     <div><strong>Instância:</strong> {{ $instance->instance_name }}</div>
                     <div><strong>API Key:</strong> {{ $instance->masked_api_key }}</div>
                     @if($instance->description)
                     <div><strong>Descrição:</strong> {{ $instance->description }}</div>
                     @endif
                  </div>
                  <div class="mt-2 mt-md-0">
                     <span class="badge badge-{{ $instance->is_active ? 'success' : 'secondary' }}">{{ $instance->is_active ? 'Ativa' : 'Inativa' }}</span>
                     @if($instance->is_default)
                     <span class="badge badge-primary">Padrão</span>
                     @endif
                  </div>
               </div>
               <div class="mt-3 d-flex gap-2">
                  <a href="{{ route('whatsapp.instances.edit', $instance->id) }}" class="btn btn-primary btn-sm mr-2">Editar</a>
                  <form method="POST" action="{{ route('whatsapp.instances.destroy', $instance->id) }}" onsubmit="return confirm('Deseja remover esta instância?')" style="display:inline;">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
                  </form>
               </div>
            </div>
            @empty
            <p class="text-muted mb-0">Nenhuma instância configurada ainda.</p>
            @endforelse
         </div>
      </div>
   </div>
</div>
@stop
