@extends('adminlte::page')
@section('plugins.sweetalert2', true)

@section('title', 'Modalidades')

@section('content_header')
<h1><i class="fas fa-fw fa-tags"></i> Modalidades {{ $unidade->nome }}</h1>
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

      <div class="table-responsive p-0">
         <table class="table table-hover text-nowrap" id="user-table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>Duração</th>
                  <th>Nível de Dificuldade</th>
                  <th>Status</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse ($modalidades as $modalidade)
               <tr>
                  <td class="align-middle">{{$modalidade->id}}</td>
                  <td class="align-middle">{{$modalidade->name}}</td>
                  <td class="align-middle">{{$modalidade->descricao}}</td>
                  <td class="align-middle">{{$modalidade->duracao}}</td>
                  <td class="align-middle">{{$modalidade->nivel_dificuldade}}</td>
                  @if($modalidade->status == true)
                  <td class="align-middle font-weight-bold">
                     <span class="badge badge-success">
                        ATIVO
                  </td>
                  @else
                  <td class="align-middle font-weight-bold">
                     <span class="badge badge-danger">
                        INATIVO
                  </td>
                  @endif
                  <td class="align-middle overflow-visible-btn " style="text-align: right">
                     <div class="btn-group">
                        <a class="btn btn-warning" href="{{ route('modalidade.edit',$modalidade->id) }}"><i class="fas fa fa-edit text-white"></i></a>
                        <a class="btn btn-success" href="{{ route('modalidade.show',$modalidade->id) }}"><i class="fas fa fa-eye"></i></a>
                        <button type="button" class="btn btn-info ativar-btn" data-id="{{ $modalidade->id }}">
                           <i class="fas fa-check"></i>
                        </button>
                     </div>
                     <form action="{{ route('modalidade.destroy', $modalidade->id) }}" style="display: inline;" onsubmit="confirmarExclusao(event, this)" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">
                           <i class="fas fa fa-trash"></i>
                        </button>
                     </form>
                  </td>
                  @empty
                  <td>Não há modalidades no banco de dados</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>

<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Cadastrar Modalidade</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('modalidade.store') }}" method="POST">
               @csrf
               <input type="hidden" name="academia_unidade_id" id="academia_unidade_id" value="{{ $unidade->id }}">
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" required>
               </div>
               <div class="form-group">
                  <label for="descricao">Descrição</label>
                  <textarea class="form-control" name="descricao" id="descricao" required></textarea>
               </div>
               <div class="form-group">
                  <label for="duracao">Duração</label>
                  <input type="text" name="duracao" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="nivel_dificuldade">Nível de Dificuldade</label>
                  <input type="nivel_dificuldade" name="nivel_dificuldade" class="form-control" required>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Criar Modalidade</button>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
</div>
@stop

@section('js')
<script>
   // Função AJAX para ativar modalidade
   $('.ativar-btn').on('click', function() {
      let id = $(this).data('id');
      Swal.fire({
         title: 'Processando...',
         text: 'Aguarde enquanto o status da modalidade é mudado.',
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
               text: 'O status da modalidade foi mudado com sucesso.',
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