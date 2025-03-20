@extends('adminlte::page')
@section('plugins.sweetalert2', true)

@section('title', 'Usuários')

@section('content_header')
<h1>Usuários</h1>
@stop

@section('content_header')
<h1>TESTE</h1>
@endsection
@section('content')
<div class="col-12">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-header">
            <div class="card-tools">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="#" class="btn btn-sm btn-primary" id="abrirModalFiltros" title="Filtrar resultados">
                        <i class="fas fa-fw fa-search"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-secondary" title="Gerar PDF" target="_blank">
                        <i class="fas fa-fw fa-file-pdf"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo item">
                        <i class="fas fa-fw fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            <a href="{{ route('user.edit',$user->id) }}"><i class="fas fa fa-edit"></i></a>
                            <a href="{{ route('user.show',$user->id) }}"><i class="fas fa fa-eye"></i></a>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="confirmarExclusao(event, this)">
                                @csrf
                                @method('DELETE')
                                <button style="border:none; background: none;" type="submit">
                                    <i class="fas fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                        @empty
                        <td>Não tem nada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.store') }}" method="POST">
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
                        <label for="password">Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirme a Senha</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Criar Usuário</button>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @stop

    @section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @stop

    @section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");

        function confirmarExclusao(event, formulario) {
            event.preventDefault();

            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processando...',
                        text: 'Aguarde enquanto o usuário é excluído.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    formulario.submit();
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