@extends('adminlte::page')
@section('title', 'Alunos')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Alunos</h1>
@stop
@section('content')
<div class="col-12">
   <div class="card">
      <div class="card-header">
         @if($errors->any() && old('_student_form') === 'create')
         <div class="alert alert-danger mb-3">
            <strong>Não foi possível salvar o aluno.</strong>
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
               @can('students.manage')
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
                  <th>Unidade</th>
                  <th>Status</th>
                  <th></th>
               </tr>
            </thead>
            <tbody id="tabela-usuarios">
               @forelse ($alunos as $aluno)
               <tr>
                  <td class="align-middle">{{$aluno->name}}</td>
                  <td class="align-middle">{{$aluno->email}}</td>
                  <td class="align-middle">
                     {{ $aluno->aluno->unidade->nome ?? '-' }}
                  </td>
                  <td class="align-middle">
                     @if($aluno->status)
                     <span class="badge badge-success text-uppercase">Ativo</span>
                     @else
                     <span class="badge badge-danger text-uppercase">Inativo</span>
                     @endif
                  </td>
                  <td class="align-middle overflow-visible-btn " style="text-align: right">
                     <div class="btn-group">
                        @can('students.manage')
                        <a class="btn btn-warning btn-sm" href="{{ route('aluno.edit',$aluno->id) }}"><i class="fas fa fa-edit text-white"></i></a>
                        @endcan
                        <a class="btn btn-success btn-sm" href="{{ route('aluno.show',$aluno->id) }}"><i class="fas fa fa-eye"></i></a>
                        @if(($paymentTransactions[$aluno->id] ?? null))
                        <a class="btn btn-primary btn-sm" href="{{ route('financeiro.transacoes.edit', $paymentTransactions[$aluno->id]->id) }}" title="Editar pagamento">
                           <i class="fas fa-money-bill-wave"></i>
                        </a>
                        @endif
                        <button type="button" class="btn btn-info btn-sm ativar-btn" data-id="{{ $aluno->id }}" data-active="{{ $aluno->status ? 1 : 0 }}">
                           <i class="fas fa-check"></i>
                        </button>
                     </div>
                     @can('students.manage')
                     <form action="{{ route('user.destroy', $aluno->id) }}" method="POST" style="display: inline;" onsubmit="confirmarExclusao(event, this)">
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
                     <i class="fas fa-info-circle"></i> Não há alunos no banco de dados.
                  </div>
               </div>
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
            <form action="{{ route('aluno.store') }}" method="POST" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="_student_form" value="create">

               {{-- NOME --}}
               <div class="form-group">
                  <label for="name">Nome Completo</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                  @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               {{-- E-MAIL --}}
               <div class="form-group">
                  <label for="email">E-mail</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                  @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               {{-- CPF --}}
               <div class="form-group">
                  <label for="cpf">CPF</label>
                  <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00">
                  @error('cpf')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               {{-- TELEFONE --}}
               <div class="form-group">
                  <label for="telefone">Telefone</label>
                  <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                  @error('telefone')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               {{-- SEXO + DATA DE NASCIMENTO --}}
               <div class="form-row">
                  <div class="col">
                     <label for="sexo">Sexo</label>
                     <select name="sexo" id="sexo" class="form-control @error('sexo') is-invalid @enderror">
                        <option value="">Selecione</option>
                        <option value="Masculino" {{ old('sexo') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Feminino" {{ old('sexo') === 'Feminino' ? 'selected' : '' }}>Feminino</option>
                     </select>
                     @error('sexo')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>

                  <div class="col">
                     <label for="data_nascimento">Data de Nascimento</label>
                     <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror" id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}">
                     @error('data_nascimento')
                     <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                  </div>
               </div>

               {{-- UNIDADE --}}
               <div class="form-group mt-3">
                  <label for="unidade_id">Unidade</label>
                  <select class="form-control @error('unidade_id') is-invalid @enderror" name="unidade_id" id="unidade_id">
                     <option value="">Selecione a Unidade</option>
                     @foreach ($unidades as $unidade)
                     <option value="{{ $unidade->id }}" {{ (string) old('unidade_id') === (string) $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                     @endforeach
                  </select>
                  @error('unidade_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               {{-- FOTO --}}
               <div class="form-group">
                  <label for="foto">Foto do Aluno</label>
                  <div class="d-flex flex-wrap mb-2">
                     <button type="button" class="btn btn-outline-primary btn-sm mr-2 mb-2" id="open-student-camera">
                        <i class="fas fa-camera mr-1"></i> Tirar foto agora
                     </button>
                     <button type="button" class="btn btn-outline-success btn-sm mr-2 mb-2 d-none" id="capture-student-camera">
                        <i class="fas fa-camera-retro mr-1"></i> Capturar
                     </button>
                     <button type="button" class="btn btn-outline-secondary btn-sm mb-2 d-none" id="close-student-camera">
                        <i class="fas fa-times mr-1"></i> Fechar câmera
                     </button>
                  </div>

                  <input
                     type="file"
                     class="form-control-file @error('foto') is-invalid @enderror"
                     id="foto"
                     name="foto"
                     accept="image/*"
                     capture="user">

                  <small class="form-text text-muted">
                     Você pode enviar um arquivo ou abrir a câmera para tirar a foto na hora.
                  </small>
                  @error('foto')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror

                  <div class="mt-3 d-none" id="student-camera-wrapper">
                     <div class="border rounded p-2 bg-dark">
                        <video
                           id="student-camera-video"
                           class="w-100 rounded"
                           autoplay
                           playsinline
                           muted
                           style="max-height: 320px; object-fit: cover;"></video>
                     </div>
                  </div>

                  <canvas id="student-camera-canvas" class="d-none"></canvas>

                  <div class="mt-3 d-none" id="student-photo-preview-wrapper">
                     <label class="d-block">Pré-visualização</label>
                     <img
                        id="student-photo-preview"
                        src=""
                        alt="Pré-visualização da foto do aluno"
                        class="img-thumbnail rounded"
                        style="width: 160px; height: 160px; object-fit: cover;">
                  </div>
               </div>

               {{-- OBSERVAÇÕES --}}
               <div class="form-group mt-3">
                  <label for="observacoes">Observações</label>
                  <textarea name="observacoes" id="observacoes" rows="3" class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
                  @error('observacoes')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
               </div>

               <div class="modal-footer px-0">
                  <button type="submit" class="btn btn-warning text-bold">Salvar Aluno</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>


<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title">Filtrar Aluno</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="GET" action="{{ route('aluno.index') }}">
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
                  <a class="text-white" href="{{ route('aluno.index') }}">
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
   @if($errors->any() && old('_student_form') === 'create')
   $('#modalDefault').modal('show');
   @endif

   $('.ativar-btn').on('click', function() {
      let id = $(this).data('id');
      let isActive = Number($(this).data('active')) === 1;
      let actionLabel = isActive ? 'cancelado' : 'ativado';
      Swal.fire({
         title: 'Processando...',
         text: isActive
            ? 'Aguarde enquanto o aluno é cancelado e os planos vinculados são removidos.'
            : 'Aguarde enquanto o aluno é ativado.',
         allowOutsideClick: false,
         allowEscapeKey: false,
         allowEnterKey: false,
         didOpen: () => {
            Swal.showLoading();
         }
      });
      $.ajax({
         url: `/aluno/${id}/toggleStatus`,
         method: 'POST',
         data: {
            _token: '{{ csrf_token() }}'
         },
         success: function() {
            Swal.fire({
               title: 'Sucesso!',
               text: `Aluno ${actionLabel} com sucesso.`,
               icon: 'success'
            }).then(() => {
               location.reload();
            })
         },
         error: function() {
            Swal.fire({
               title: 'Erro!',
               text: 'Houve um problema ao mudar o status do aluno.',
               icon: 'error'
            })
         }
      });
   });
</script>

<script>
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
