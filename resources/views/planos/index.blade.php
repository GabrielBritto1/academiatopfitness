@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-file-invoice-dollar"></i> Planos</h1>
@stop

@section('content')
@can('admin')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <a href="{{ route('planos.carrinho') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-fw fa-shopping-cart"></i> Carrinho de planos
         </a>
         <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo plano">
            <i class="fas fa-fw fa-plus"></i>
         </a>
      </div>
   </div>
   @endcan

   <div class="container-fluid">
      @foreach ($planosPorUnidade as $unidadeNome => $planos)
      <div class="my-2">
         <h3 class="text-bold text-uppercase">{{ $unidadeNome }}</h3>
         <div class="row">
            <div class="col-12">
               <div class="row">
                  @forelse($planos as $plano)
                  <div class="col-md-4">
                     <div class="card">
                        <div class="card-header" style="background-color: {{ $plano->color }};">
                           <h3 class="card-title text-bold text-white text-uppercase">{{ $plano->name }}</h3>
                        </div>
                        <div class="card-body">
                           <ul class="list-group">
                              @foreach ($plano->beneficios as $beneficio)
                              <li class="list-group-item">
                                 <i class="fas fa-check text-success"></i> {{ $beneficio->beneficio }}
                              </li>
                              @endforeach
                           </ul>
                           <h4 class="mt-3 text-muted text-sm">ATÉ O DIA <span class="text-bold text-lg">{{ $plano->dia_vencimento }}: R$ {{ $plano->preco_pre_vencimento }}/mês</span></h4>
                           <h4 class="text-muted text-sm">APÓS O DIA <span class="text-bold text-lg">{{ $plano->dia_vencimento }}: R$ {{ $plano->preco_pos_vencimento }}/mês</span></h4>
                           <img src="{{ asset('img/iso logo mono.png') }}" alt="Iso Logo" class="img-fluid float-right" style="max-width: 100px; opacity: 0.3;">
                        </div>
                        <div class="card-footer text-center">
                           <!-- <button class="btn btn-warning" onclick="alert('Plano {{ $plano->name }} Selecionado')">Selecionar Plano</button> -->
                           @can('admin')
                           <div class="btn-group">
                              <a class="btn btn-sm bg-warning" href="{{ route('planos.edit', $plano->id) }}">
                                 <i class="fas fa-edit"></i>
                              </a>
                              <a class="btn btn-sm bg-danger" href="#">
                                 <i class="fas fa-trash"></i>
                              </a>
                              @endcan
                           </div>
                        </div>
                     </div>
                  </div>
                  @empty
                  <div class="col-12">
                     <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhum plano encontrado.
                     </div>
                  </div>
                  @endforelse
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</div>

<!-- MODALS -->
<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Criar Novo Plano</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('planos.store') }}" method="POST">
               @csrf
               <div class="form-group">
                  <label for="academia_unidade_id">Unidade</label>
                  <select class="form-control" id="academia_unidade_id" name="academia_unidade_id" required>
                     <option value="" selected disabled>Selecione uma Unidade</option>
                     @foreach ($unidades as $unidade)
                     <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label for="name">Nome do Plano</label>
                  <input type="text" class="form-control" id="name" name="name" required>
               </div>
               <div class="form-group">
                  <label for="preco_pre_vencimento">Preço antes do vencimento (R$)</label>
                  <input type="number" step="0.01" class="form-control" id="preco_pre_vencimento" name="preco_pre_vencimento" required>
               </div>
               <div class="form-group">
                  <label for="preco_pos_vencimento">Preço após o vencimento (R$)</label>
                  <input type="number" step="0.01" class="form-control" id="preco_pos_vencimento" name="preco_pos_vencimento" required>
               </div>
               <div class="form-group">
                  <label for="dia_vencimento">Dia do vencimento</label>
                  <input type="number" class="form-control" id="dia_vencimento" name="dia_vencimento" required>
               </div>
               <div class="form-group">
                  <label for="beneficios">Benefícios</label>
                  <div id="beneficios-container">
                     <!-- Benefícios serão adicionados aqui dinamicamente -->
                  </div>
                  <button type="button" id="add-beneficio" class="btn btn-sm btn-secondary mt-2">
                     <i class="fas fa-plus"></i> Adicionar Benefício
                  </button>
               </div>
               <div class="form-group">
                  <label for="color">Cor do Plano</label>
                  <input type="color" name="color" class="form-control" required>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Criar Plano</button>
            </form>
         </div>
      </div>
   </div>
</div>
@stop

@section('css')
<style>
   .planos-card:hover {
      transform: scale(1.05);
      transition: all 0.3s ease-in-out;
      z-index: 1;
   }
</style>
@stop

@section('js')
<script>
   $(document).ready(function() {
      let beneficioCount = 0;

      // Adicionar novo campo de benefício
      $('#add-beneficio').click(function() {
         beneficioCount++;
         $('#beneficios-container').append(`
            <div class="beneficio-item input-group mb-2" data-id="${beneficioCount}">
                <input type="text" name="beneficios[${beneficioCount}][descricao]" 
                       class="form-control" placeholder="Descrição do benefício" required>
                <input type="number" name="beneficios[${beneficioCount}][ordem]" 
                       class="form-control" placeholder="Ordem" value="${beneficioCount}" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-beneficio">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
      });

      // Remover benefício
      $(document).on('click', '.remove-beneficio', function() {
         $(this).closest('.beneficio-item').remove();
      });

      // Enviar formulário via AJAX
      $('#planoForm').submit(function(e) {
         e.preventDefault();

         $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
               // Fechar modal e recarregar a página ou atualizar a tabela
               $('#modalDefault').modal('hide');
               window.location.reload(); // Ou atualize apenas a seção necessária
            },
            error: function(xhr) {
               alert('Erro: ' + xhr.responseJSON.message);
            }
         });
      });
   });
</script>

@if(session('success'))
<script>
   document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
         icon: 'success',
         title: 'Sucesso!',
         text: "{{ session('success') }}",
      });
   });
</script>
@endif
@if(session('error'))
<script>
   document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
         icon: 'error',
         title: 'Erro!',
         text: "{{ session('error') }}",
      });
   });
</script>
@endif
@stop