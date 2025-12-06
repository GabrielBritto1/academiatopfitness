@extends('adminlte::page')

@section('title', 'Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Treino {{ $treino->sigla }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
      <div>
         <h4 class="mb-0">Treino {{ $treino->sigla }}</h4>
         @if($treino->nome)
         <small class="text-muted">{{ $treino->nome }}</small>
         @endif
         @if($treino->dias_semana)
         <small class="text-muted">({{ $treino->dias_semana }})</small>
         @endif
      </div>
      <div>
         <a href="{{ route('treino.edit', $treino->id) }}" class="btn btn-sm btn-warning">Editar Treino</a>
         <form action="{{ route('treino.destroy', $treino->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este treino?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Excluir Treino</button>
         </form>
      </div>
   </div>

   <div class="card-body">
      @if($treino->observacoes)
      <div class="alert alert-info">
         <strong>Observações:</strong> {{ $treino->observacoes }}
      </div>
      @endif

      <div class="d-flex justify-content-between align-items-center mb-3">
         <h5 class="mb-0">Exercícios</h5>
         <a href="{{ route('treino-exercicio.create', ['treino_id' => $treino->id]) }}" class="btn btn-sm btn-success">
            + Adicionar Exercício
         </a>
      </div>

      @if($treino->exercicios->count())
      <table class="table table-bordered">
         <thead>
            <tr>
               <th style="width: 80px;">Ordem</th>
               <th>Exercício</th>
               <th>Séries</th>
               <th>Repetições</th>
               <th>Carga</th>
               <th>Descanso</th>
               <th>Observação</th>
               <th style="width: 150px;">Ações</th>
            </tr>
         </thead>
         <tbody>
            @foreach($treino->exercicios as $ex)
            <tr>
               <td>{{ $ex->ordem }}</td>
               <td><strong>{{ $ex->nome }}</strong></td>
               <td>{{ $ex->series ?? '—' }}</td>
               <td>{{ $ex->repeticoes ?? '—' }}</td>
               <td>{{ $ex->carga ?? '—' }}</td>
               <td>{{ $ex->descanso ?? '—' }}</td>
               <td>{{ $ex->observacao ?? '—' }}</td>
               <td>
                  <a href="{{ route('treino-exercicio.edit', $ex->id) }}" class="btn btn-sm btn-info">Editar</a>
                  <form action="{{ route('treino-exercicio.destroy', $ex->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este exercício?');">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
      @else
      <div class="alert alert-warning">
         Nenhum exercício cadastrado neste treino. <a href="{{ route('treino-exercicio.create', ['treino_id' => $treino->id]) }}">Clique aqui para adicionar o primeiro exercício.</a>
      </div>
      @endif
   </div>

   <div class="card-footer">
      <a href="{{ route('planilha-treino.show', $treino->planilha_id) }}" class="btn btn-secondary">Voltar para a Planilha</a>
   </div>
</div>
@stop

