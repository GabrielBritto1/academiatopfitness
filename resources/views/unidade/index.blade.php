@extends('adminlte::page')
@section('plugins.sweetalert2', true)

@section('title', 'Modalidades')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-fw fa-tags"></i> Modalidades</h1>
@stop

@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <div class="card-tools">
            <div class="btn-group" role="group" aria-label="...">
               <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalFilter" title="Filtrar modalidade">
                  <i class="fas fa-fw fa-search"></i>
               </a>
               <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar nova modalidade">
                  <i class="fas fa-fw fa-plus"></i>
               </a>
            </div>
         </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
         <div class="container-fluid">
            <div class="row">
               @forelse ($unidades as $unidade)
               <div class="col-sm-6 col-lg-4">
                  <a href="{{ route('unidade.modalidadesUnidade', $unidade->id) }}" style="color: black">
                     <div class="info-box">
                        <span class="info-box-icon bg-secondary">
                           <img src="{{ asset('img/iso logo mono.png') }}" alt="unidade 1" class="img-fluid" style="width: 50px;">
                        </span>
                        <div class="info-box-content">
                           <span class="info-box-text">{{ $unidade->nome }}</span>
                        </div>
                     </div>
                  </a>
               </div>
               @empty
               <div class="col-12">
                  <div class="alert alert-info">
                     <i class="fas fa-info-circle"></i> Não há unidades no banco de dados.
                  </div>
               </div>
               @endforelse
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title">Cadastrar Unidade</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
               </button>
            </div>
            <div class="modal-body">
               <form action="{{ route('unidade.store') }}" method="POST">
                  @csrf
                  <div class="form-group">
                     <label for="nome">Nome</label>
                     <input type="text" class="form-control" id="nome" name="nome" required>
                  </div>
                  <div class="form-group">
                     <label for="endereco">Endereço</label>
                     <input type="text" class="form-control" name="endereco" id="endereco" required></input>
                  </div>
                  <button type="submit" class="btn btn-warning text-bold">Adicionar Unidade</button>
               </form>
            </div>
            <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
      </div>
   </div>
   @stop

   @section('css')
   {{-- Add here extra stylesheets --}}
   {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
   @stop

   @section('js')
   <script src="/js/Modalidade/index.js"></script>

   <script>
      // Função AJAX para ativar modalidade
      $('.ativar-btn').on('click', function() {
         let id = $(this).data('id');
         Swal.fire({
            title: 'Processando...',
            text: 'Aguarde enquanto a modalidade é ativada.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
               Swal.showLoading();
            }
         });
         $.ajax({
            url: `/modalidade/ativar/${id}`,
            method: 'POST',
            data: {
               _token: '{{ csrf_token() }}'
            },
            success: function() {
               Swal.fire({
                  title: 'Ativado!',
                  text: 'A modalidade foi ativada com sucesso.',
                  icon: 'success'
               }).then(() => {
                  location.reload();
               })
            },
            error: function() {
               Swal.fire({
                  title: 'Erro!',
                  text: 'Houve um problema ao mudar o status a modalidade.',
                  icon: 'error'
               })
            }
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
   @stop