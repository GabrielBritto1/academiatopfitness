@extends('adminlte::page')
@section('plugins.sweetalert2', true)

@section('title', 'Usuários')

@section('content_header')
<h1><i class="fas fa-fw fa-users"></i> Usuários</h1>
@stop

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo item">
                        <i class="fas fa-fw fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap" id="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Perfil</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tabela-usuarios">
                    @forelse ($users as $user)
                    <tr>
                        <td class="align-middle">{{$user->id}}</td>
                        <td class="align-middle">{{$user->name}}</td>
                        <td class="align-middle">{{$user->email}}</td>
                        @foreach ($user->perfil as $perfil)
                        <td class="align-middle">{{$perfil->formatted_name}}</td>
                        @endforeach
                        <td class="align-middle overflow-visible-btn " style="text-align: right">
                            <div class="btn-group">
                                <a class="btn btn-warning" href="{{ route('user.edit',$user->id) }}"><i class="fas fa fa-edit text-white"></i></a>
                                <a class="btn btn-success" href="{{ route('user.show',$user->id) }}"><i class="fas fa fa-eye"></i></a>
                            </div>
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="confirmarExclusao(event, this)">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">
                                    <i class="fas fa fa-trash"></i>
                                </button>
                            </form>
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
    <!-- /.card -->
</div>

<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Inserir Usuário</h4>
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
                        <label for="perfil">Perfil</label>
                        <select name="perfil" class="form-control">
                            <option value="" class="disabled">Selecione um perfil...</option>
                            @foreach ($perfils as $perfil)
                            <option value="{{ $perfil->id }}">{{ $perfil->formatted_name }}</option>
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
    @stop

    @section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @stop

    @section('js')
    <script src="/js/User/index.js"></script>

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