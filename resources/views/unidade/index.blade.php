@extends('adminlte::page')
@section('plugins.sweetalert2', true)

@section('title', 'Unidades')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-fw fa-chalkboard"></i> Unidades</h1>
@stop

@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <div class="card-tools">
            <div class="btn-group" role="group" aria-label="...">
               <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar nova unidade">
                  <i class="fas fa-fw fa-plus"></i> Nova Unidade
               </a>
            </div>
         </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
         <div class="container-fluid">
            <div class="row">
               @forelse ($unidades as $unidade)
               <div class="col-sm-6 col-lg-4 mb-4">
                  <div class="card shadow-sm h-100">
                     <div class="card-body text-center">
                        <div class="mb-3">
                           @if($unidade->logo)
                           <img src="{{ asset('storage/' . $unidade->logo) }}" 
                                alt="{{ $unidade->nome }}" 
                                class="img-fluid rounded" 
                                style="max-height: 120px; max-width: 100%; object-fit: contain;">
                           @else
                           <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                style="height: 120px;">
                              <i class="fas fa-building fa-3x text-white"></i>
                           </div>
                           @endif
                        </div>
                        <h5 class="card-title font-weight-bold mb-2">{{ $unidade->nome }}</h5>
                        <p class="card-text text-muted small mb-3">
                           <i class="fas fa-map-marker-alt"></i> {{ $unidade->endereco }}
                        </p>
                        <div class="btn-group" role="group">
                           <a href="{{ route('unidade.edit', $unidade->id) }}" 
                              class="btn btn-warning btn-sm text-white">
                              <i class="fas fa-edit"></i> Editar
                           </a>
                        </div>
                     </div>
                  </div>
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
               <form action="{{ route('unidade.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                     <label for="nome">Nome *</label>
                     <input type="text" class="form-control" id="nome" name="nome" required>
                  </div>
                  <div class="form-group">
                     <label for="endereco">Endereço *</label>
                     <input type="text" class="form-control" name="endereco" id="endereco" required>
                  </div>
                  <div class="form-group">
                     <label for="logo">Logo da Unidade</label>
                     <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                        <label class="custom-file-label" for="logo">Escolher arquivo...</label>
                     </div>
                     <small class="form-text text-muted">Formatos aceitos: JPG, PNG, GIF, SVG (máx. 2MB)</small>
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
   <script>
      // Preview do logo no modal de criação
      $('#logo').on('change', function() {
         const file = this.files[0];
         if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
               // Preview pode ser adicionado aqui se necessário
            };
            reader.readAsDataURL(file);
            $(this).next('.custom-file-label').html(file.name);
         } else {
            $(this).next('.custom-file-label').html('Escolher arquivo...');
         }
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