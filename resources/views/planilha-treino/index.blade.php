@extends('adminlte::page')

@section('title', 'Planilha Treino')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-dumbbell"></i> Planilha Treino</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools">
         <div class="btn-group" role="group" aria-label="...">
            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalFilter" title="Filtrar usuário">
               <i class="fas fa-fw fa-search"></i>
            </a>
            <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalDefault" title="Adicionar novo usuário">
               <i class="fas fa-fw fa-plus"></i>
            </a>
         </div>
      </div>
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
                  <th>Data da criação da Planilha</th>
                  <th>Data da modificação da Planilha</th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               @forelse ($planilhas as $planilha)
               <tr>
                  <td class="align-middle">{{ $planilha->unidade->nome }}</td>
                  <td class="align-middle">{{ $planilha->professor->name }}</td>
                  <td class="align-middle">{{ $planilha->aluno->name }}</td>
                  <td class="align-middle">{{ $planilha->plano->name }}</td>
                  <td class="align-middle">{{ $planilha->created_at->format('d/m/Y') }}</td>
                  <td class="align-middle">{{ $planilha->updated_at->format('d/m/Y') }}</td>
                  <td class="align-middle">
                     <div class="btn-group">
                        <a href="{{ route('planilha-treino.edit', $planilha->id) }}" class="btn btn-warning text-white"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('planilha-treino.planilha_treino_pdf', $planilha->id) }}" class="btn btn-info"><i class="fas fa-file-alt"></i></a>
                     </div>
                  </td>
               </tr>
               @empty
               <tr>
                  <td colspan="6">Nenhuma planilha cadastrada.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
</div>
@stop