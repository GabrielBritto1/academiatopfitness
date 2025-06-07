@extends('adminlte::page')
@section('title', 'Alunos')
@section('content_header')
<h1><i class="fas fa-chalkboard-teacher"></i> Alunos</h1>
@stop
@section('content')
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         <div class="card-tools">
            <div class="btn-group" role="group" aria-label="...">
               <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalFilter" title="Filtrar usuário">
                  <i class="fas fa-fw fa-search"></i>
               </a>
               @can('admin')
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
                  <th>Modalidade</th>
                  <th></th>
               </tr>
            </thead>
            <tbody id="tabela-usuarios">
               @forelse ($alunos as $aluno)
               <tr>
                  <td class="align-middle">{{$aluno->name}}</td>
                  <td class="align-middle">{{$aluno->email}}</td>
                  @foreach ($aluno->roles as $role)
                  <td class="align-middle">
                     {{$role->formatted_name}}
                  </td>
                  @endforeach
                  <td class="align-middle">
                     @foreach ($aluno->modalidadesUnidades as $modalidade)
                     {{ \App\Models\AcademiaUnidade::find($modalidade->pivot->academia_unidade_id)->nome ?? '-' }}<br>
                     @endforeach
                  </td>
                  <td class="align-middle">
                     @foreach ($aluno->modalidadesUnidades as $modalidade)
                     {{ $modalidade->name }}<br>
                     @endforeach
                  </td>
                  <td class="align-middle overflow-visible-btn " style="text-align: right">
                     <div class="btn-group">
                        @can('admin')
                        <a class="btn btn-warning" href="{{ route('aluno.edit',$aluno->id) }}"><i class="fas fa fa-edit text-white"></i></a>
                        @endcan
                        <a class="btn btn-success" href="{{ route('aluno.show',$aluno->id) }}"><i class="fas fa fa-eye"></i></a>
                        <a class="btn btn-info" href="{{ route('aluno.show',$aluno->id) }}"><i class="fas fa fa-check"></i></a>
                     </div>
                     @can('admin')
                     <form action="{{ route('user.destroy', $aluno->id) }}" method="POST" style="display: inline;" onsubmit="confirmarExclusao(event, this)">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">
                           <i class="fas fa fa-trash"></i>
                        </button>
                     </form>
                     @endcan
                  </td>
                  @empty
                  <td>Não há usuários no banco de dados</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
      <!-- /.card-body -->
   </div>
   {{ $alunos->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
   <!-- /.card -->
</div>

<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Inserir Aluno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="{{ route('aluno.store') }}" method="POST">
               @csrf
               <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" required>
               </div>
               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
               </div>
               <div class="form-group">
                  <label for="academia_unidade_id">Unidade</label>
                  <select class="form-control" name="academia_unidade_id" id="academia_unidade_id">
                     <option value="">Selecione a Unidade</option>
                     @foreach ($unidades as $unidade)
                     <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label for="modalidade_id">Modalidade</label>
                  <select class="form-control" name="modalidade_id" id="modalidade_id" disabled>
                     <option value="">Selecione a Modalidade</option>
                     @foreach ($unidades as $unidade)
                     <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group">
                  <label for="password">Senha</label>
                  <input type="password" name="password" class="form-control" required>
               </div>
               <div class="form-group">
                  <label for="password_confirmation">Confirme a Senha</label>
                  <input type="password" name="password_confirmation" class="form-control" required>
               </div>
               <button type="submit" class="btn btn-warning text-bold">Criar Usuário</button>
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
   // SCRIPT PARA CARREGAR AS MODALIDADES BASEADO NA UNIDADE SELECIONADA
   const unidades = @json($unidades);
   document.getElementById('academia_unidade_id').addEventListener('change', function() {
      const unidadeId = this.value;
      const modalidadeSelect = document.getElementById('modalidade_id');
      modalidadeSelect.innerHTML = '<option value="">Selecione a Modalidade</option>';

      if (!unidadeId) {
         modalidadeSelect.disabled = true;
         return;
      }
      // Busca a unidade selecionada
      const unidade = unidades.find(u => u.id == unidadeId);
      if (unidade && unidade.modalidades.length > 0) {
         unidade.modalidades.forEach(function(modalidade) {
            modalidadeSelect.innerHTML += `<option value="${modalidade.id}">${modalidade.name}</option>`;
         });
         modalidadeSelect.disabled = false;
      } else {
         modalidadeSelect.disabled = true;
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