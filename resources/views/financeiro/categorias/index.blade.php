@extends('adminlte::page')

@section('title', 'Categorias Financeiras')

@section('content_header')
<h1 class="text-bold"><i class="fas fa-tags"></i> Categorias Financeiras</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
         <div class="btn-group btn-group-sm mb-2 mb-md-0" role="group">
            <a href="{{ route('financeiro.categorias.index', array_filter(['redirect_to' => $redirectTo])) }}" class="btn btn-{{ $type === null ? 'primary' : 'default' }}">Todas</a>
            <a href="{{ route('financeiro.categorias.index', array_filter(['type' => 'receita', 'redirect_to' => $redirectTo])) }}" class="btn btn-{{ $type === 'receita' ? 'success' : 'default' }}">Receitas</a>
            <a href="{{ route('financeiro.categorias.index', array_filter(['type' => 'despesa', 'redirect_to' => $redirectTo])) }}" class="btn btn-{{ $type === 'despesa' ? 'danger' : 'default' }}">Despesas</a>
         </div>
         <div class="card-tools">
            <button type="button" class="btn btn-sm btn-success" onclick="abrirNovaCategoria()">
               <i class="fas fa-fw fa-plus"></i> Nova Categoria
            </button>
         </div>
      </div>
   </div>
   <div class="card-body">
      @if(session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger">
         {{ session('error') }}
      </div>
      @endif

      @if($errors->any())
      <div class="alert alert-danger">
         <strong>Não foi possível salvar a categoria.</strong>
         <ul class="mb-0 mt-2 pl-3">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
         </ul>
      </div>
      @endif

      @if($redirectTo)
      <div class="alert alert-info">
         Depois de salvar a categoria, o sistema volta para a tela financeira de origem.
      </div>
      @endif
   </div>
   <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
         <thead>
            <tr>
               <th>Nome</th>
               <th>Tipo</th>
               <th>Status</th>
               <th>Usos</th>
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
               <td>{{ $category->transactions_count }}</td>
               <td>
                  <button
                     type="button"
                     class="btn btn-sm btn-warning"
                     data-id="{{ $category->id }}"
                     data-name="{{ $category->name }}"
                     data-type="{{ $category->type }}"
                     data-active="{{ $category->is_active ? '1' : '0' }}"
                     onclick="editarCategoria(this)"
                  >
                     <i class="fas fa-edit"></i>
                  </button>
                  <form action="{{ route('financeiro.categorias.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar esta categoria?');">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-sm btn-danger" {{ $category->transactions_count > 0 ? 'disabled' : '' }}>
                        <i class="fas fa-trash"></i>
                     </button>
                  </form>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="5" class="text-center">Nenhuma categoria cadastrada.</td>
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
         <form id="formCategoria" method="POST" action="{{ route('financeiro.categorias.store') }}">
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
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                  @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="form-group">
                  <label for="type">Tipo</label>
                  <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                     <option value="receita" {{ old('type', $type) === 'receita' ? 'selected' : '' }}>Receita</option>
                     <option value="despesa" {{ old('type', $type) === 'despesa' ? 'selected' : '' }}>Despesa</option>
                  </select>
                  @error('type')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="form-check">
                  <input type="hidden" name="is_active" value="0">
                  <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">Ativa</label>
               </div>
               <input type="hidden" id="redirect_to" name="redirect_to" value="{{ $redirectTo }}">
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
   function abrirNovaCategoria() {
      $('#modalCategoriaTitle').text('Nova Categoria');
      $('#formCategoria').attr('action', '{{ route("financeiro.categorias.store") }}');
      $('#methodField').html('');
      $('#name').val(@json(old('name', '')));
      $('#type').val(@json(old('type', $type ?? 'receita')));
      $('#is_active').prop('checked', {{ old('is_active', true) ? 'true' : 'false' }});
      $('#redirect_to').val(@json($redirectTo));
      $('#modalCategoria').modal('show');
   }

   function editarCategoria(button) {
      const id = $(button).data('id');
      const name = $(button).data('name');
      const type = $(button).data('type');
      const isActive = String($(button).data('active')) === '1';

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
      $('#type').val(@json($type ?? 'receita'));
      $('#redirect_to').val(@json($redirectTo));
   });

   @if($errors->any())
   $('#modalCategoria').modal('show');
   @endif
</script>
@endpush
@stop
