@extends('adminlte::page')

@section('title', 'Categorias Financeiras')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-tags"></i> Categorias Financeiras</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalCategoria">
            <i class="fas fa-fw fa-plus"></i> Nova Categoria
         </button>
      </div>
   </div>
   <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
         <thead>
            <tr>
               <th>Nome</th>
               <th>Tipo</th>
               <th>Status</th>
               <th>Ações</th>
            </tr>
         </thead>
         <tbody>
            @forelse($categories as $category)
            <tr>
               <td>{{ $category->name }}</td>
               <td>
                  @if($category->type === 'receita')
                  <span class="badge badge-success">Receita</span>
                  @else
                  <span class="badge badge-danger">Despesa</span>
                  @endif
               </td>
               <td>
                  @if($category->is_active)
                  <span class="badge badge-success">Ativa</span>
                  @else
                  <span class="badge badge-secondary">Inativa</span>
                  @endif
               </td>
               <td>
                  <button type="button" class="btn btn-sm btn-warning" onclick="editarCategoria({{ $category->id }}, '{{ $category->name }}', '{{ $category->type }}', {{ $category->is_active ? 'true' : 'false' }})">
                     <i class="fas fa-edit"></i>
                  </button>
                  <form action="{{ route('financeiro.categorias.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta categoria?');">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                     </button>
                  </form>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="4" class="text-center">Nenhuma categoria cadastrada.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>

<!-- Modal Criar/Editar Categoria -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <form id="formCategoria" method="POST">
            @csrf
            <div id="methodField"></div>
            <div class="modal-header">
               <h5 class="modal-title" id="modalCategoriaTitle">Nova Categoria</h5>
               <button type="button" class="close" data-dismiss="modal">
                  <span>&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label for="name">Nome da Categoria</label>
                  <input type="text" class="form-control" id="name" name="name" required>
               </div>
               <div class="form-group">
                  <label for="type">Tipo</label>
                  <select class="form-control" id="type" name="type" required>
                     <option value="receita">Receita</option>
                     <option value="despesa">Despesa</option>
                  </select>
               </div>
               <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                  <label class="form-check-label" for="is_active">Ativa</label>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
               <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
         </form>
      </div>
   </div>
</div>

@push('js')
<script>
   function editarCategoria(id, name, type, isActive) {
      $('#modalCategoriaTitle').text('Editar Categoria');
      $('#formCategoria').attr('action', '{{ url("/financeiro/categorias") }}/' + id);
      $('#methodField').html('<input type="hidden" name="_method" value="PUT">');
      $('#name').val(name);
      $('#type').val(type);
      $('#is_active').prop('checked', isActive);
      $('#modalCategoria').modal('show');
   }

   $('#modalCategoria').on('hidden.bs.modal', function () {
      $('#modalCategoriaTitle').text('Nova Categoria');
      $('#formCategoria').attr('action', '{{ route("financeiro.categorias.store") }}');
      $('#methodField').html('');
      $('#formCategoria')[0].reset();
      $('#is_active').prop('checked', true);
   });
</script>
@endpush
@stop
