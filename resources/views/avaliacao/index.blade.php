@extends('adminlte::page')
@section('title', 'Avaliações')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-clipboard-list"></i> Avaliações</h1>
@stop
@section('content')
<div class="card">
   <div class="card-body">
      <table class="table table-hover text-nowrap">
         <thead>
            <tr>
               <th>Nome</th>
               <th>Email</th>
               <th>Ações</th>
            </tr>
         </thead>
         <tbody>
            @forelse($alunos as $aluno)
            <tr>
               <td>{{ $aluno->name }}</td>
               <td>{{ $aluno->email }}</td>
               <td>
                  <a href="{{ route('avaliacao.create', ['aluno_id' => $aluno->id, 'professor_id' => Auth::user()->id]) }}" class="btn btn-sm btn-primary">
                     <i class="fas fa-fw fa-plus"></i> Nova Avaliação
                  </a>
                  <a href="{{ route('avaliacao.show', $aluno->id) }}" class="btn btn-sm btn-info">
                     <i class="fas fa-fw fa-eye"></i> Ver Avaliações
                  </a>
               </td>
               @empty
               <div class="alert alert-info">
                  <strong>Info!</strong> Nenhum aluno encontrado.
               </div>
            </tr>
            @endforelse
         </tbody>
      </table>
   </div>
</div>
@stop