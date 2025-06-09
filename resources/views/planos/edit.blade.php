@extends('adminlte::page')
@section('title', 'Editar Plano')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-invoice-dollar"></i> Editar Plano</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <a href="{{ route('planos.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-fw fa-arrow-left"></i> Voltar aos Planos
         </a>
      </div>
   </div>
   <div class="card-body">
      <form action="{{ route('planos.update', $planos->id) }}" method="POST">
         @csrf
         @method('PUT')
         <div class="form-group">
            <label for="name">Nome do Plano</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $planos->name }}" required>
         </div>
         <div class="form-group">
            <label for="preco_pre_vencimento">Preço antes do vencimento (R$)</label>
            <input type="number" step="0.01" class="form-control" id="preco_pre_vencimento" name="preco_pre_vencimento" value="{{ $planos->preco_pre_vencimento }}" required>
         </div>
         <div class="form-group">
            <label for="preco_pos_vencimento">Preço após o vencimento (R$)</label>
            <input type="number" step="0.01" class="form-control" id="preco_pos_vencimento" name="preco_pos_vencimento" value="{{ $planos->preco_pos_vencimento }}" required>
         </div>
         <div class="form-group">
            <label for="dia_vencimento">Dia do vencimento</label>
            <input type="number" class="form-control" id="dia_vencimento" name="dia_vencimento" value="{{ $planos->dia_vencimento }}" required>
         </div>
         <div class="form-group">
            <label for="beneficios">Benefícios</label>
            <div id="beneficios-container">
               @foreach ($planos->beneficios as $beneficio)
               <div class="beneficio-item input-group mb-2" data-id="{{ $beneficio->id }}">
                  <input type="text" name="beneficios[{{ $beneficio->id }}][descricao]" class="form-control" placeholder="Descrição do benefício" value="{{ $beneficio->descricao }}" required>
                  <input type="number" name="beneficios[{{ $beneficio->id }}][ordem]" class="form-control" placeholder="Ordem" value="{{ $beneficio->ordem }}" required>
                  <div class="input-group-append">
                     <button type="button" class="btn btn-danger remove-beneficio">
                        <i class="fas fa-trash"></i>
                     </button>
                  </div>
               </div>
               @endforeach
            </div>
            <button type="button" id="add-beneficio" class="btn btn-sm btn-secondary mt-2">
               <i class="fas fa-plus"></i> Adicionar Benefício
            </button>
         </div>
         <div class="form-group">
            <label for="color">Cor do Plano</label>
            <input type="color" name="color" class="form-control" value="{{ $planos->color }}" required>
         </div>
         <div class="form-group">
            <label for="status">Unidade</label>
            <select class="form-control" id="academia_unidade_id" name="academia_unidade_id" required>
               <option value="" selected disabled>Selecione uma Unidade</option>
               @foreach ($unidades as $unidade)
               <option value="{{ $unidade->id }}" {{ $planos->academia_unidade_id == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
               @endforeach
            </select>
         </div>
         <button type="submit" class="btn btn-warning text-bold">Editar Plano</button>
      </form>
   </div>
</div>
@stop