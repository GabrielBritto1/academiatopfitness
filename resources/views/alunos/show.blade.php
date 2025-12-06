@extends('adminlte::page')

@section('title', $user->name)

@section('content_header')
<h1 class="text-bold">{{ $user->name }}</h1>
@stop

@section('content')

<div class="card shadow-sm">

   {{-- FOTO + DADOS PRINCIPAIS --}}
   <div class="card-body pb-0">
      <div class="row">

         {{-- FOTO DO ALUNO --}}
         <div class="col-md-3 text-center mb-4">
            <img
               src="{{ $aluno->foto ? asset('storage/'.$aluno->foto) : 'https://marketplace.canva.com/A5alg/MAESXCA5alg/1/tl/canva-user-icon-MAESXCA5alg.png' }}"
               class="img-thumbnail rounded-circle shadow-sm"
               style="width:150px; height:150px; object-fit:cover;"
               alt="Foto do aluno">

            <div class="mt-2 text-muted">
               <small>{{ $user->name }}</small>
            </div>
         </div>

         {{-- DADOS PRINCIPAIS --}}
         <div class="col-md-9">
            <div class="row">

               <div class="col-md-4 mb-3">
                  <strong>CPF:</strong><br>
                  {{ $aluno->cpf ?? '—' }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>E-mail:</strong><br>
                  {{ $user->email }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Data de Cadastro:</strong><br>
                  {{ $user->created_at->format('d/m/Y') }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Unidade:</strong><br>
                  {{ $aluno->unidade->nome ?? '—' }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Status:</strong><br>
                  <span class="badge badge-{{ $user->status == true ? 'success' : 'danger' }}">
                     {{ $user->status == true ? 'ATIVO' : 'INATIVO' }}
                  </span>
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Última Atualização:</strong><br>
                  {{ $aluno->updated_at->format('d/m/Y H:i') }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Total de Avaliações Físicas:</strong><br>
                  {{ $avaliacoes->count() }}
               </div>

            </div>
         </div>
      </div>
   </div>

   <hr class="mt-0">

   {{-- ABAS --}}
   <div class="card-body p-0">

      <ul class="nav nav-tabs">
         <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#ficha">📄 Ficha Técnica</a>
         </li>

         <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#avaliacoes">🏋️ Avaliações Físicas</a>
         </li>

         <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#planos">💳 Planos</a>
         </li>

         <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#treino">📝 Ficha de Treino</a>
         </li>
      </ul>

      <div class="tab-content p-3">

         {{-- TAB FICHA --}}
         <div id="ficha" class="tab-pane fade show active">
            <h4 class="mb-3">Informações do Aluno</h4>

            <div class="row">
               <div class="col-md-4 mb-3">
                  <strong>Idade:</strong><br>
                  {{ $aluno->idade ?? '—' }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Telefone:</strong><br>
                  {{ $aluno->telefone ?? '—' }}
               </div>

               <div class="col-md-4 mb-3">
                  <strong>Sexo:</strong><br>
                  {{ $aluno->sexo ?? '—' }}
               </div>
            </div>

            <div class="row">
               <div class="col-12 mb-3">
                  <strong>Observações:</strong><br>
                  {{ $aluno->observacoes ?? 'Nenhuma observação registrada.' }}
               </div>
            </div>
         </div>

         {{-- TAB AVALIAÇÕES --}}
         <div id="avaliacoes" class="tab-pane fade">
            <h4 class="mb-3">Avaliações Físicas</h4>

            @if($avaliacoes->count())
            <ul class="list-group">
               @foreach($avaliacoes as $av)
               <li class="list-group-item d-flex justify-content-between">
                  <div>
                     <strong>Avaliação {{ $loop->iteration }}</strong><br>
                     IMC: {{ $av->imc }} — Gordura: {{ $av->gordura }}%
                  </div>
                  <small>{{ $av->created_at->format('d/m/Y') }}</small>
               </li>
               @endforeach
            </ul>
            @else
            <p class="text-muted">Nenhuma avaliação registrada.</p>
            @endif
         </div>

         {{-- TAB PLANOS --}}
         <div id="planos" class="tab-pane fade">
            <h4 class="mb-3">Planos Contratados</h4>

            @if($planos->count())
            <ul class="list-group">
               @foreach($planos as $plano)
               <li class="list-group-item">
                  <strong>{{ $plano->name }}</strong>
               </li>
               @endforeach
            </ul>
            @else
            <p class="text-muted">Nenhum plano ativo.</p>
            @endif
         </div>

         {{-- TAB FICHA DE TREINO --}}
         <div id="treino" class="tab-pane fade">
            <a class="btn btn-warning mb-3" href="{{ route('planilha-treino.create', ['aluno_id' => $user->id]) }}">+ Criar Planilha de Treino</a>
            <a class="btn btn-primary mb-3" href="{{ route('planilha-treino.planilha_treino_pdf', $user->id) }}">📄 PDF Completo</a>

            @if($planilhas->count())

            @foreach($planilhas as $planilha)

            <div class="card mb-3">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div>
                     <h4 class="mb-0">Planilha criada em {{ $planilha->created_at->format('d/m/Y') }}</h4>
                     <small class="text-muted">
                        Professor: {{ $planilha->professor->name ?? '—' }} | 
                        Unidade: {{ $planilha->unidade->nome ?? '—' }}
                     </small>
                  </div>
                  <div>
                     <a href="{{ route('planilha-treino.show', $planilha->id) }}" class="btn btn-sm btn-info">Ver Detalhes</a>
                     <a href="{{ route('planilha-treino.edit', $planilha->id) }}" class="btn btn-sm btn-warning">Editar</a>
                  </div>
               </div>
               <div class="card-body">
            @foreach($planilha->treinos as $treino)
            <div class="card mb-2">
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
                  <a href="{{ route('treino.show', $treino->id) }}" class="btn btn-sm btn-info">Ver/Editar</a>
               </div>

               <div class="card-body">
                  @if($treino->exercicios->count())
                  <ul class="mb-0">
                     @foreach($treino->exercicios as $ex)
                     <li>
                        <strong>{{ $ex->nome }}</strong> —
                        {{ $ex->series }}x{{ $ex->repeticoes }}
                        @if($ex->carga) ({{ $ex->carga }} | {{ $ex->descanso }} descanso) @endif
                     </li>
                     @endforeach
                  </ul>
                  @else
                  <p class="text-muted mb-0">Nenhum exercício no treino {{ $treino->sigla }}.</p>
                  @endif
               </div>
            </div>
            @endforeach
               </div>
            </div>
            @endforeach
            @else
            <p class="text-muted">Nenhuma planilha de treino criada.</p>
            @endif
         </div>
      </div>
   </div>
</div>
@stop