@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1></h1>
@stop

@section('content')

{{-- CARDS DE ESTATÍSTICAS --}}
<div class="row">
   <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3>{{ $totalAlunos }}</h3>
            <p>Alunos Cadastrados</p>
         </div>
         <div class="icon">
            <i class="fas fa-users"></i>
         </div>
         <a href="{{ route('aluno.index') }}" class="small-box-footer">
            Ver todos <i class="fas fa-arrow-circle-right"></i>
         </a>
      </div>
   </div>

   <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3>{{ $totalProfessores }}</h3>
            <p>Professores Cadastrados</p>
         </div>
         <div class="icon">
            <i class="fas fa-chalkboard-teacher"></i>
         </div>
         <a href="{{ route('professor.index') }}" class="small-box-footer">
            Ver todos <i class="fas fa-arrow-circle-right"></i>
         </a>
      </div>
   </div>

   <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
         <div class="inner">
            <h3>{{ $totalPlanos }}</h3>
            <p>Planos Disponíveis</p>
         </div>
         <div class="icon">
            <i class="fas fa-file-invoice-dollar"></i>
         </div>
         <a href="{{ route('planos.index') }}" class="small-box-footer">
            Ver todos <i class="fas fa-arrow-circle-right"></i>
         </a>
      </div>
   </div>

   <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
         <div class="inner">
            <h3>{{ $totalUnidades }}</h3>
            <p>Unidades da Academia</p>
         </div>
         <div class="icon">
            <i class="fas fa-building"></i>
         </div>
         <a href="{{ route('unidade.index') }}" class="small-box-footer">
            Ver todas <i class="fas fa-arrow-circle-right"></i>
         </a>
      </div>
   </div>
</div>

{{-- LINKS RÁPIDOS --}}
<div class="row">
   <div class="col-md-6">
      <div class="card card-info card-outline">
         <div class="card-header">
            <h3 class="card-title">
               <i class="fas fa-clipboard-list"></i> Acesso Rápido - Avaliações
            </h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-6 mb-3">
                  <a href="{{ route('avaliacao.index') }}" class="btn btn-info btn-block btn-lg">
                     <i class="fas fa-list"></i><br>
                     <small>Ver Todas as Avaliações</small>
                  </a>
               </div>
               <div class="col-md-6 mb-3">
                  <a href="{{ route('avaliacao.create') }}" class="btn btn-success btn-block btn-lg">
                     <i class="fas fa-plus"></i><br>
                     <small>Nova Avaliação</small>
                  </a>
               </div>
            </div>
            <div class="info-box mb-3">
               <span class="info-box-icon bg-info"><i class="fas fa-clipboard-check"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Total de Avaliações</span>
                  <span class="info-box-number">{{ $totalAvaliacoes }}</span>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="col-md-6">
      <div class="card card-warning card-outline">
         <div class="card-header">
            <h3 class="card-title">
               <i class="fas fa-dumbbell"></i> Acesso Rápido - Planilhas de Treino
            </h3>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-6 mb-3">
                  <a href="{{ route('planilha-treino.index') }}" class="btn btn-warning btn-block btn-lg">
                     <i class="fas fa-list"></i><br>
                     <small>Ver Todas as Planilhas</small>
                  </a>
               </div>
               <div class="col-md-6 mb-3">
                  <a href="{{ route('planilha-treino.create') }}" class="btn btn-success btn-block btn-lg">
                     <i class="fas fa-plus"></i><br>
                     <small>Nova Planilha</small>
                  </a>
               </div>
            </div>
            <div class="info-box mb-3">
               <span class="info-box-icon bg-warning"><i class="fas fa-dumbbell"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Total de Planilhas</span>
                  <span class="info-box-number">{{ $totalPlanilhas }}</span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

{{-- ÚLTIMAS ATIVIDADES --}}
<div class="row">
   <div class="col-md-6">
      <div class="card">
         <div class="card-header border-transparent">
            <h3 class="card-title">
               <i class="fas fa-history"></i> Últimas Avaliações
            </h3>
         </div>
         <div class="card-body p-0">
            <div class="table-responsive">
               <table class="table m-0">
                  <thead>
                     <tr>
                        <th>Aluno</th>
                        <th>Data</th>
                        <th>Professor</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($ultimasAvaliacoes as $avaliacao)
                     <tr>
                        <td>{{ $avaliacao->aluno->name ?? '—' }}</td>
                        <td>{{ $avaliacao->created_at->format('d/m/Y') }}</td>
                        <td>{{ $avaliacao->professor->name ?? '—' }}</td>
                        <td>
                           <a href="{{ route('avaliacao.view_pdf', $avaliacao->id) }}"
                              class="btn btn-sm btn-info"
                              target="_blank"
                              title="Ver PDF">
                              <i class="fas fa-eye"></i>
                           </a>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="4" class="text-center text-muted">Nenhuma avaliação recente</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
         <div class="card-footer clearfix">
            <a href="{{ route('avaliacao.index') }}" class="btn btn-sm btn-info float-right">
               Ver todas as avaliações
            </a>
         </div>
      </div>
   </div>

   <div class="col-md-6">
      <div class="card">
         <div class="card-header border-transparent">
            <h3 class="card-title">
               <i class="fas fa-history"></i> Últimas Planilhas de Treino
            </h3>
         </div>
         <div class="card-body p-0">
            <div class="table-responsive">
               <table class="table m-0">
                  <thead>
                     <tr>
                        <th>Aluno</th>
                        <th>Data</th>
                        <th>Professor</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($ultimasPlanilhas as $planilha)
                     <tr>
                        <td>{{ $planilha->aluno->name ?? '—' }}</td>
                        <td>{{ $planilha->created_at->format('d/m/Y') }}</td>
                        <td>{{ $planilha->professor->name ?? '—' }}</td>
                        <td>
                           <a href="{{ route('planilha-treino.show', $planilha->id) }}"
                              class="btn btn-sm btn-warning"
                              title="Ver detalhes">
                              <i class="fas fa-eye"></i>
                           </a>
                        </td>
                     </tr>
                     @empty
                     <tr>
                        <td colspan="4" class="text-center text-muted">Nenhuma planilha recente</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
         <div class="card-footer clearfix">
            <a href="{{ route('planilha-treino.index') }}" class="btn btn-sm btn-warning float-right">
               Ver todas as planilhas
            </a>
         </div>
      </div>
   </div>
</div>

@stop

@section('css')
<style>
   .small-box {
      border-radius: 0.25rem;
      box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
      display: block;
      margin-bottom: 20px;
      position: relative;
   }

   .small-box>.inner {
      padding: 10px;
   }

   .small-box>.small-box-footer {
      background-color: rgba(0, 0, 0, 0.1);
      color: rgba(255, 255, 255, 0.8);
      display: block;
      padding: 3px 0;
      position: relative;
      text-align: center;
      text-decoration: none;
      z-index: 10;
   }

   .small-box .icon {
      color: rgba(0, 0, 0, 0.15);
      z-index: 0;
   }

   .small-box .icon>i {
      font-size: 70px;
      position: absolute;
      right: 15px;
      top: 15px;
      transition: transform 0.3s linear;
   }

   .small-box:hover .icon>i {
      transform: scale(1.1);
   }
</style>
@stop