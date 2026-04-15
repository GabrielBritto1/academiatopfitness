@extends('adminlte::page')
@section('title', 'Professores')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-chalkboard-teacher"></i> Professores</h1>
@stop
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         @if($errors->any() && old('_professor_form') === 'create')
         <div class="alert alert-danger mb-3">
            <strong>Não foi possível salvar o professor.</strong>
            <ul class="mb-0 mt-2 pl-3">
               @foreach($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif

         <div class="card-tools">
            <div class="btn-group" role="group" aria-label="...">
               <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalFilter" title="Filtrar usuário">
                  <i class="fas fa-fw fa-search"></i>
               </a>
               @can('professors.manage')
               <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo usuário">
                  <i class="fas fa-fw fa-plus"></i>
               </a>
               @endcan
            </div>
         </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
         <table class="table table-hover text-nowrap" id="user-table">
            <thead>
               <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Cargo</th>
                  <th>Unidade</th>
                  <th>Plano</th>
                  <th>Status</th>
                  <th></th>
               </tr>
            </thead>
            <tbody id="tabela-usuarios">
               @forelse ($professores as $professor)
               <tr>
                  <td class="align-middle">{{$professor->name}}</td>
                  <td class="align-middle">{{$professor->email}}</td>
                  <td class="align-middle">
                     {{ $professor->roles->pluck('formatted_name')->implode(', ') ?: '—' }}
                  </td>
                  <td class="align-middle">
                     @forelse ($professor->planos as $plano)
                     {{ \App\Models\AcademiaUnidade::find($plano->pivot->academia_unidade_id)->nome ?? '-' }}<br>
                     @empty
                     <span class="text-muted">Nenhum plano associado</span>
                     @endforelse
                  </td>
                  <td class="align-middle">
                     @forelse ($professor->planos as $plano)
                     {{ $plano->name }}<br>
                     @empty
                     <span class="text-muted">Nenhum plano associado</span>
                     @endforelse
                  </td>
                  <td class="align-middle">
                     @if($professor->status)
                     <span class="badge badge-success text-uppercase">Ativo</span>
                     @else
                     <span class="badge badge-danger text-uppercase">Inativo</span>
                     @endif
                  </td>
                  <td class="align-middle overflow-visible-btn " style="text-align: right">
                     <div class="btn-group">
                        @can('users.manage')
                        <a class="btn btn-warning btn-sm" href="{{ route('user.edit',$professor->id) }}"><i class="fas fa fa-edit text-white"></i></a>
                        @endcan
                        @can('users.manage')
                        <a class="btn btn-success btn-sm" href="{{ route('user.show',$professor->id) }}"><i class="fas fa fa-eye"></i></a>
                        @endcan
                     </div>
                     @can('users.manage')
                     <form action="{{ route('user.destroy', $professor->id) }}" method="POST" style="display: inline;" onsubmit="confirmarExclusao(event, this)">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">
                           <i class="fas fa fa-trash"></i>
                        </button>
                     </form>
                     @endcan
                  </td>
               </tr>
               @empty
               <div class="col-12">
                  <div class="alert alert-info">
                     <i class="fas fa-info-circle"></i> Não há professor no banco de dados.
                  </div>
               </div>
               @endforelse
            </tbody>
         </table>
      </div>
      <!-- /.card-body -->
   </div>
   {{ $professores->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
   <!-- /.card -->
</div>

<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Inserir Professor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('professor.store') }}" method="POST">
               @csrf
               <input type="hidden" name="_professor_form" value="create">
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                  @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                  @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-warning text-bold">Inserir Professor</button>
               </div>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
</div>

<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Filtrar Professor</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="GET" action="{{ route('professor.index') }}">
               <div class="form-group">
                  <label for="search">Nome</label>
                  <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
               </div>
               <div class="form-group">
                  <label for="status">Status</label>
                  <select class="form-control" name="status" id="status">
                     <option value="">Todos</option>
                     <option value="1">Ativo</option>
                     <option value="0">Inativo</option>
                  </select>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Filtrar</button>
               <button type="submit" class="btn btn-secondary text-bold">
                  <a class="text-white" href="{{ route('professor.index') }}">
                     Limpar Filtros
                  </a>
               </button>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
</div>
@stop

@section('js')
<script src="/js/User/index.js"></script>

<script>
   @if($errors->any() && old('_professor_form') === 'create')
   $('#modalDefault').modal('show');
   @endif

   // SCRIPT PARA CARREGAR OS PLANOS BASEADO NA UNIDADE SELECIONADA
   const unidades = @json($unidades);
   const academiaUnidadeField = document.getElementById('academia_unidade_id');
   const planoField = document.getElementById('plano_id');

   if (academiaUnidadeField && planoField) {
      academiaUnidadeField.addEventListener('change', function() {
      const unidadeId = this.value;
      planoField.innerHTML = '<option value="">Selecione o Plano</option>';

      if (!unidadeId) {
         planoField.disabled = true;
         return;
      }
      // Busca a unidade selecionada
      const unidade = unidades.find(u => u.id == unidadeId);
      if (unidade && unidade.planos.length > 0) {
         unidade.planos.forEach(function(plano) {
            planoField.innerHTML += `<option value="${plano.id}">${plano.name}</option>`;
         });
         planoField.disabled = false;
      } else {
         planoField.disabled = true;
      }
      });
   }
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
