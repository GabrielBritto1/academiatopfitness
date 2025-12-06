@extends('adminlte::page')

@section('title', 'Planilhas de Treino')
@section('content_header')
<div class="d-flex justify-content-between align-items-center">
   <h1 class="text-bold"><i class="fas fa-dumbbell"></i> Planilhas de Treino</h1>
   <a href="{{ route('planilha-treino.create') }}" class="btn btn-success">
      <i class="fas fa-plus"></i> Criar Planilha
   </a>
</div>
@stop

@section('content')

{{-- PLANILHAS PADRÃO --}}
<div class="card">
   <div class="card-header bg-primary">
      <h3 class="card-title text-white"><i class="fas fa-star"></i> Planilhas Padrão</h3>
      <div class="card-tools">
         <a href="{{ route('planilha-treino.create', ['is_padrao' => 1]) }}" class="btn btn-sm btn-light">
            <i class="fas fa-plus"></i> Criar Planilha Padrão
         </a>
      </div>
   </div>
   <div class="card-body">
      <div class="table-responsive p-0">
         <table class="table table-hover text-nowrap">
            <thead>
               <tr>
                  <th>Nome</th>
                  <th>Unidade</th>
                  <th>Professor(a)</th>
                  <th>Plano</th>
                  <th>Treinos</th>
                  <th>Data da criação</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse ($planilhasPadrao as $planilha)
               <tr>
                  <td class="align-middle">
                     <strong>{{ $planilha->nome ?? 'Sem nome' }}</strong>
                     <span class="badge badge-primary ml-2">Padrão</span>
                  </td>
                  <td class="align-middle">{{ $planilha->unidade->nome ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->professor->name ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->plano->name ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->treinos->count() }} treino(s)</td>
                  <td class="align-middle">{{ $planilha->created_at->format('d/m/Y') }}</td>
                  <td class="align-middle">
                     <div class="btn-group">
                        <a href="{{ route('planilha-treino.show', $planilha->id) }}" class="btn btn-sm btn-info" title="Ver detalhes">
                           <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('planilha-treino.edit', $planilha->id) }}" class="btn btn-sm btn-warning text-white" title="Editar">
                           <i class="fas fa-edit"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="7" class="text-center text-muted">
                     Nenhuma planilha padrão cadastrada. 
                     <a href="{{ route('planilha-treino.create', ['is_padrao' => 1]) }}">Criar uma agora</a>
                  </td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>

{{-- PLANILHAS PERSONALIZADAS (DOS ALUNOS) --}}
<div class="card mt-4">
   <div class="card-header">
      <h3 class="card-title"><i class="fas fa-users"></i> Planilhas dos Alunos</h3>
   </div>
   <div class="card-body">
      <div class="table-responsive p-0">
         <table class="table table-hover text-nowrap">
            <thead>
               <tr>
                  <th>Unidade</th>
                  <th>Professor(a)</th>
                  <th>Aluno(a)</th>
                  <th>Plano</th>
                  <th>Data da criação</th>
                  <th>Data da modificação</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse ($planilhas as $planilha)
               <tr>
                  <td class="align-middle">{{ $planilha->unidade->nome ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->professor->name ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->aluno->name ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->plano->name ?? '—' }}</td>
                  <td class="align-middle">{{ $planilha->created_at->format('d/m/Y') }}</td>
                  <td class="align-middle">{{ $planilha->updated_at->format('d/m/Y') }}</td>
                  <td class="align-middle">
                     <div class="btn-group">
                        <a href="{{ route('planilha-treino.show', $planilha->id) }}" class="btn btn-sm btn-info" title="Ver detalhes">
                           <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('planilha-treino.edit', $planilha->id) }}" class="btn btn-sm btn-warning text-white" title="Editar">
                           <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('planilha-treino.planilha_treino_pdf', $planilha->aluno_id) }}" class="btn btn-sm btn-primary" title="PDF">
                           <i class="fas fa-file-alt"></i>
                        </a>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="7" class="text-center text-muted">Nenhuma planilha de aluno cadastrada.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
@stop