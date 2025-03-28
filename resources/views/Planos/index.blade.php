@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
<h1><i class="fas fa-file-invoice-dollar"></i> Planos</h1>
@stop

@section('content')
@can('admin')
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo plano">
                <i class="fas fa-fw fa-plus"></i>
            </a>
        </div>
    </div>
</div>
@endcan

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row mt-4">
                @forelse($planos as $plano)
                <div class="col-sm-3 mb-3">
                    <div class="position-relative p-3 bg-dark elevation-3 planos-card" style="height: auto">
                        <div class="ribbon-wrapper ribbon-xl">
                            <div class="ribbon text-lg" style="background-color: {{ $plano->color }};">
                                {{ $plano->name }}
                            </div>
                        </div>
                        <h1 class="text-bold">R$ {{ $plano->preco }}<span class="text-sm text-muted">/por mês</span></h1>
                        <ul>
                            @foreach ($plano->beneficios as $beneficio)
                            <li class="list-group mb-2">{{ $beneficio->beneficio }}</li>
                            @endforeach
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-warning text-bold" onclick="alert('Plano {{ $plano->name }} Selecionado')">Selecionar Plano</button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right bg-dark">
                                    @can('admin')
                                    <a class="dropdown-item bg-warning" href="{{ route('planos.edit', $plano->id) }}">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    @endcan
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('planos.destroy', $plano->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item bg-danger" type="submit">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p>Não há nenhum plano cadastrado</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- MODALS -->
<div class="modal fade" id="modalDefault" tabindex="-1" role="dialog" aria-labelledby="modalDefaultLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Criar Novo Plano</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('planos.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome do Plano</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço (R$)</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
                    </div>
                    <div class="form-group">
                        <label for="beneficios">Benefícios</label>
                        <div id="beneficios-container">
                            <!-- Benefícios serão adicionados aqui dinamicamente -->
                        </div>
                        <button type="button" id="add-beneficio" class="btn btn-sm btn-secondary mt-2">
                            <i class="fas fa-plus"></i> Adicionar Benefício
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="color">Cor do Plano</label>
                        <input type="color" name="color" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning text-bold">Criar Plano</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .planos-card:hover {
        transform: scale(1.05);
        transition: all 0.3s ease-in-out;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let beneficioCount = 0;

        // Adicionar novo campo de benefício
        $('#add-beneficio').click(function() {
            beneficioCount++;
            $('#beneficios-container').append(`
            <div class="beneficio-item input-group mb-2" data-id="${beneficioCount}">
                <input type="text" name="beneficios[${beneficioCount}][descricao]" 
                       class="form-control" placeholder="Descrição do benefício" required>
                <input type="number" name="beneficios[${beneficioCount}][ordem]" 
                       class="form-control" placeholder="Ordem" value="${beneficioCount}" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger remove-beneficio">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);
        });

        // Remover benefício
        $(document).on('click', '.remove-beneficio', function() {
            $(this).closest('.beneficio-item').remove();
        });

        // Enviar formulário via AJAX
        $('#planoForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Fechar modal e recarregar a página ou atualizar a tabela
                    $('#modalDefault').modal('hide');
                    window.location.reload(); // Ou atualize apenas a seção necessária
                },
                error: function(xhr) {
                    alert('Erro: ' + xhr.responseJSON.message);
                }
            });
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
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: "{{ session('error') }}",
        });
    });
</script>
@endif
@stop