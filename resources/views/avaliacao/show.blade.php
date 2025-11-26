@extends('adminlte::page')
@section('title', 'Avaliações')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-clipboard-list"></i> Avaliações do aluno {{ $aluno->name }}</h1>
@stop
@section('content')
<div class="card">
   <div class="card-header">
      <div class="card-tools d-flex">
         <a href="{{ route('avaliacao.avaliacao_pdf', $aluno->id) }}" class="btn btn-info mx-1">
            <i class="fas fa-fw fa-file-pdf"></i> Gerar documento das avaliações
         </a>
         <a href="{{ route('avaliacao.avaliacao_grafico', $aluno->id) }}" class="btn btn-secondary">
            <i class="fas fa-fw fa-chart-bar"></i> Gráfico das Avaliações
         </a>
      </div>
   </div>
   <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap">
         <thead>
            <tr>
               <th>Data da avaliação</th>
               <th>Professor Avaliador</th>
               <th></th>
            </tr>
         </thead>
         <tbody>
            @forelse($avaliacoes as $avaliacao)
            <tr>
               <td class="align-middle">{{ $avaliacao->created_at->format('d/m/Y') }}</td>
               <td class="align-middle">{{ $avaliacao->professor->name }}</td>
               <td class="align-middle">
                  <div class="btn-group">
                     <a href="#" class="btn btn-success btn-sm">
                        <i class="fas fa-fw fa-eye"></i>
                     </a>
                     <!-- <a href="{{ route('avaliacao.edit', $avaliacao->id) }}" class="btn btn-warning text-white">
                        <i class="fas fa-fw fa-edit"></i>
                     </a>
                     <a href="{{ route('avaliacao.destroy', $avaliacao->id) }}" class="btn btn-danger">
                        <i class="fas fa-fw fa-trash"></i>
                     </a> -->
                  </div>
               </td>
            </tr>
            @empty
            <tr>
               <td colspan="9">Nenhuma avaliação encontrada.</td>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop