@extends('adminlte::page')

@section('title', 'Planilha de Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Planilha de Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
      <div>
         <h4 class="mb-0">
            @if($planilha->is_padrao)
            <span class="badge badge-primary mr-2">PLANILHA PADRÃO</span>
            {{ $planilha->nome ?? 'Sem nome' }}
            @else
            Planilha criada em {{ $planilha->created_at->format('d/m/Y') }}
            @endif
         </h4>
         <small class="text-muted">
            @if($planilha->is_padrao)
            Professor: <strong>{{ $planilha->professor->name ?? '—' }}</strong> | 
            Unidade: <strong>{{ $planilha->unidade->nome ?? '—' }}</strong>
            @if($planilha->plano)
            | Plano: <strong>{{ $planilha->plano->name }}</strong>
            @endif
            @else
            Aluno: <strong>{{ $planilha->aluno->name ?? '—' }}</strong> | 
            Professor: <strong>{{ $planilha->professor->name ?? '—' }}</strong> | 
            Unidade: <strong>{{ $planilha->unidade->nome ?? '—' }}</strong>
            @if($planilha->plano)
            | Plano: <strong>{{ $planilha->plano->name }}</strong>
            @endif
            @endif
         </small>
      </div>
      <div>
         <a href="{{ route('planilha-treino.edit', $planilha->id) }}" class="btn btn-sm btn-warning">Editar</a>
         @if(!$planilha->is_padrao && $planilha->aluno_id)
         <a href="{{ route('planilha-treino.planilha_treino_pdf', $planilha->aluno_id) }}" class="btn btn-sm btn-primary">📄 PDF</a>
         @endif
         <form action="{{ route('planilha-treino.destroy', $planilha->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta planilha?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
         </form>
      </div>
   </div>

   <div class="card-body">
      @if($planilha->observacoes)
      <div class="alert alert-info">
         <strong>Observações:</strong> {{ $planilha->observacoes }}
      </div>
      @endif

      <div class="d-flex justify-content-between align-items-center mb-3">
         <h5 class="mb-0">Treinos</h5>
         <a href="{{ route('treino.create', ['planilha_id' => $planilha->id]) }}" class="btn btn-sm btn-success">
            + Adicionar Treino
         </a>
      </div>

      @if($planilha->treinos->count())
      @foreach($planilha->treinos as $treino)
      <div class="card mb-3">
         <div class="card-header d-flex justify-content-between align-items-center">
            <div>
               <strong>Treino {{ $treino->sigla }}</strong>
               @if($treino->nome)
               <small class="text-muted">— {{ $treino->nome }}</small>
               @endif
               @if($treino->dias_semana)
               <small class="text-muted">({{ $treino->dias_semana }})</small>
               @endif
            </div>
            <div>
               <a href="{{ route('treino.show', $treino->id) }}" class="btn btn-sm btn-info">Ver/Editar</a>
               <form action="{{ route('treino.destroy', $treino->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este treino?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
               </form>
            </div>
         </div>
         <div class="card-body">
            @if($treino->exercicios->count())
            <table class="table table-sm table-bordered">
               <thead>
                  <tr>
                     <th>Ordem</th>
                     <th>Exercício</th>
                     <th>Séries</th>
                     <th>Repetições</th>
                     <th>Carga</th>
                     <th>Descanso</th>
                     <th>Obs</th>
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
                  </tr>
                  @endforeach
               </tbody>
            </table>
            @else
            <p class="text-muted mb-0">Nenhum exercício cadastrado neste treino.</p>
            @endif

            @if($treino->observacoes)
            <div class="mt-2">
               <small class="text-muted"><strong>Observações do treino:</strong> {{ $treino->observacoes }}</small>
            </div>
            @endif
         </div>
      </div>
      @endforeach
      @else
      <div class="alert alert-warning">
         Nenhum treino cadastrado nesta planilha. <a href="{{ route('treino.create', ['planilha_id' => $planilha->id]) }}">Clique aqui para adicionar o primeiro treino.</a>
      </div>
      @endif
   </div>

   <div class="card-footer">
      @if($planilha->is_padrao)
      <a href="{{ route('planilha-treino.index') }}" class="btn btn-secondary">Voltar para Planilhas</a>
      @elseif($planilha->aluno_id)
      <a href="{{ route('aluno.show', $planilha->aluno_id) }}" class="btn btn-secondary">Voltar para o Aluno</a>
      @else
      <a href="{{ route('planilha-treino.index') }}" class="btn btn-secondary">Voltar</a>
      @endif
   </div>
</div>
@stop

