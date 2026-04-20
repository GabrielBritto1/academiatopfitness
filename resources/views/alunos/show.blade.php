@extends('adminlte::page')

@section('title', $user->name)

@section('content_header')
<h1></h1>
@stop

@section('content')

@php
$openBillingTab = session('open_billing_tab')
   || session('open_whatsapp_tab')
   || $errors->has('whatsapp_instance_id')
   || $errors->has('message')
   || $errors->has('billing_email_message');
$openBirthdayTab = session('open_birthday_tab') || $errors->has('birthday_email_message') || $errors->has('birthday_whatsapp_message') || $errors->has('birthday_whatsapp_instance_id');
@endphp

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">

   {{-- FOTO + DADOS PRINCIPAIS --}}
   <div class="card-body pb-0">
      <div class="row">

         {{-- FOTO DO ALUNO --}}
         <div class="col-md-3 text-center mb-4">
            <img
               src="{{ $aluno->foto_url }}"
               class="img-thumbnail rounded-circle shadow-sm"
               style="width:150px; height:150px; object-fit:cover;"
               alt="Foto do aluno">

            <div class="mt-2 text-muted">
               <small>{{ $user->name ?? '-' }}</small>
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
            <a class="nav-link {{ ($openBillingTab || $openBirthdayTab) ? '' : 'active' }}" data-toggle="tab" href="#ficha">📄 Ficha Técnica</a>
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

         @if($billingAlert)
         <li class="nav-item">
            <a class="nav-link {{ $openBillingTab ? 'active' : '' }}" data-toggle="tab" href="#whatsapp-cobranca" id="whatsapp-cobranca-tab">💬 Aviso de Cobrança</a>
         </li>
         @endif

         @if($birthdayGreeting)
         <li class="nav-item">
            <a class="nav-link {{ $openBirthdayTab ? 'active' : '' }}" data-toggle="tab" href="#birthday-greetings" id="birthday-greetings-tab">🎉 Parabéns</a>
         </li>
         @endif
      </ul>

      <div class="tab-content p-3">

         {{-- TAB FICHA --}}
         <div id="ficha" class="tab-pane fade {{ ($openBillingTab || $openBirthdayTab) ? '' : 'show active' }}">
            <h4 class="mb-3">Informações do Aluno</h4>

            <div class="row">
               <div class="col-md-4 mb-3">
                  <strong>Data de Nascimento:</strong><br>
                  {{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}
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

            <div class="mb-4">
               <strong>Acesso Rápido:</strong>
               <div class="mt-2 d-flex flex-wrap">
                  <a href="{{ route('avaliacao.create', ['aluno_id' => $user->id, 'professor_id' => auth()->id()]) }}" class="btn btn-sm btn-success mr-2 mb-2">
                     <i class="fas fa-plus-circle"></i> Fazer Avaliação Física
                  </a>
                  <a href="{{ route('avaliacao.show', $user->id) }}" class="btn btn-sm btn-info mr-2 mb-2">
                     <i class="fas fa-chart-line"></i> Ver Histórico de Avaliações
                  </a>
                  <a href="{{ route('planos.carrinho', ['user_id' => $user->id]) }}" class="btn btn-sm btn-warning mr-2 mb-2">
                     <i class="fas fa-credit-card"></i> Vincular Plano
                  </a>
                  @if($paymentTransaction)
                  <a href="{{ route('financeiro.transacoes.edit', $paymentTransaction->id) }}" class="btn btn-sm btn-primary mb-2">
                     <i class="fas fa-money-check-alt"></i> Ajustar Pagamento
                  </a>
                  @endif
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
               @php($planPaymentTransaction = $planPaymentTransactions[$plano->pivot->id] ?? null)
               <li class="list-group-item">
                  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                     <div class="mb-2 mb-md-0 pr-md-3">
                        <strong>{{ $plano->name }}</strong><br>
                        <small class="text-muted">
                           Periodicidade: {{ ucfirst($plano->pivot->periodicidade ?? 'mensal') }}
                           |
                           Vencimento: {{ isset($plano->pivot->data_vencimento) && $plano->pivot->data_vencimento ? \Carbon\Carbon::parse($plano->pivot->data_vencimento)->format('d/m/Y') : '—' }}
                           |
                           Forma de pagamento: {{ ucfirst($plano->pivot->forma_pagamento ?? 'nao informada') }}
                        </small>
                        @if($planPaymentTransaction)
                        <div class="mt-2">
                           <span class="badge badge-{{ $planPaymentTransaction->status === 'vencido' ? 'danger' : 'warning' }}">
                              {{ $planPaymentTransaction->status === 'vencido' ? 'Pagamento vencido' : 'Pagamento pendente' }}
                           </span>
                           <small class="text-muted ml-2">
                              Cobrança: {{ $planPaymentTransaction->due_date?->format('d/m/Y') ?? '—' }} |
                              Valor: R$ {{ number_format($planPaymentTransaction->amount - $planPaymentTransaction->discount + $planPaymentTransaction->addition, 2, ',', '.') }}
                           </small>
                        </div>
                        @endif
                     </div>

                     <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center">
                        <a href="{{ route('aluno.planos.edit', [$user->id, $plano->pivot->id]) }}" class="btn btn-sm btn-outline-primary mb-2 mb-sm-0 {{ $planPaymentTransaction ? 'mr-sm-2' : '' }}">
                           <i class="fas fa-edit"></i> Editar Plano
                        </a>

                        @if($planPaymentTransaction)
                        <form action="{{ route('financeiro.transacoes.marcar-pago', $planPaymentTransaction->id) }}" method="POST" class="mb-0">
                           @csrf
                           <button type="submit" class="btn btn-sm btn-success">
                              <i class="fas fa-check-circle"></i> Confirmar Pagamento
                           </button>
                        </form>
                        @endif
                     </div>
                  </div>
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

         @if($billingAlert)
         <div id="whatsapp-cobranca" class="tab-pane fade {{ $openBillingTab ? 'show active' : '' }}">
            <div class="alert alert-{{ $billingAlert['is_overdue'] ? 'danger' : 'warning' }}">
               <div class="d-flex justify-content-between align-items-center flex-wrap">
                  <div>
                     <strong>{{ $billingAlert['title'] }}</strong><br>
                     {{ $billingAlert['summary'] }}
                  </div>
                  <span class="badge {{ $billingAlert['status_badge_class'] }}">{{ $billingAlert['status_label'] }}</span>
               </div>
            </div>

            <div class="row">
               <div class="col-lg-6">
                  <div class="card card-outline card-primary h-100">
                     <div class="card-header">
                        <h3 class="card-title">Aviso por E-mail</h3>
                     </div>
                     <form method="POST" action="{{ route('aluno.billing.email.send', $user->id) }}">
                        @csrf
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6 mb-3">
                                 <strong>Parcela:</strong><br>
                                 {{ $billingAlert['transaction']->description }}
                              </div>
                              <div class="col-md-6 mb-3">
                                 <strong>E-mail do aluno:</strong><br>
                                 {{ $user->email ?? 'Não cadastrado' }}
                              </div>
                           </div>

                           <div class="form-group">
                              <label for="billing_email_message">Mensagem</label>
                              <textarea name="billing_email_message" id="billing_email_message" rows="6" class="form-control @error('billing_email_message') is-invalid @enderror" required>{{ old('billing_email_message', $billingAlert['message']) }}</textarea>
                              @error('billing_email_message')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                        </div>
                        <div class="card-footer">
                           <button type="submit" class="btn btn-primary">
                              <i class="fas fa-envelope"></i> Enviar por e-mail
                           </button>
                        </div>
                     </form>
                  </div>
               </div>

               <div class="col-lg-6">
                  <div class="card card-outline card-{{ $billingAlert['is_overdue'] ? 'danger' : 'warning' }} h-100">
                     <div class="card-header">
                        <h3 class="card-title">Aviso por WhatsApp</h3>
                     </div>
                     <form method="POST" action="{{ route('aluno.whatsapp.billing-alert.send', $user->id) }}">
                        @csrf
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6 mb-3">
                                 <strong>Vencimento:</strong><br>
                                 {{ $billingAlert['transaction']->due_date?->format('d/m/Y') ?? '—' }}
                              </div>
                              <div class="col-md-6 mb-3">
                                 <strong>Telefone do aluno:</strong><br>
                                 {{ $aluno->telefone ?? 'Não cadastrado' }}
                              </div>
                           </div>

                           @if($whatsappInstances->isEmpty())
                           <div class="alert alert-info mb-0">
                              Nenhuma instância ativa do WhatsApp foi cadastrada ainda.
                              <a href="{{ route('whatsapp.instances.index') }}">Cadastrar agora</a>.
                           </div>
                           @else
                           <div class="form-group">
                              <label for="whatsapp_instance_id">Instância do WhatsApp</label>
                              <select name="whatsapp_instance_id" id="whatsapp_instance_id" class="form-control @error('whatsapp_instance_id') is-invalid @enderror" required>
                                 <option value="">Selecione</option>
                                 @foreach($whatsappInstances as $instance)
                                 <option value="{{ $instance->id }}" {{ (string) old('whatsapp_instance_id', $whatsappInstances->firstWhere('is_default', true)?->id) === (string) $instance->id ? 'selected' : '' }}>
                                    {{ $instance->name }}{{ $instance->is_default ? ' (Padrão)' : '' }}
                                 </option>
                                 @endforeach
                              </select>
                              @error('whatsapp_instance_id')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label for="message">Mensagem</label>
                              <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ old('message', $billingAlert['message']) }}</textarea>
                              @error('message')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           @endif
                        </div>
                        @if($whatsappInstances->isNotEmpty())
                        <div class="card-footer">
                           <button type="submit" class="btn btn-success">
                              <i class="fab fa-whatsapp"></i> Enviar por WhatsApp
                           </button>
                        </div>
                        @endif
                     </form>
                  </div>
               </div>
            </div>
         </div>
         @endif

         @if($birthdayGreeting)
         <div id="birthday-greetings" class="tab-pane fade {{ $openBirthdayTab ? 'show active' : '' }}">
            <div class="alert alert-success">
               <strong>Hoje é o aniversário de {{ $user->name }} 🎂</strong><br>
               Você pode enviar parabéns por e-mail ou WhatsApp a partir desta aba.
            </div>

            <div class="row">
               <div class="col-lg-6">
                  <div class="card card-outline card-success">
                     <div class="card-header">
                        <h3 class="card-title">Parabéns por E-mail</h3>
                     </div>
                     <form method="POST" action="{{ route('aluno.birthday.email.send', $user->id) }}">
                        @csrf
                        <div class="card-body">
                           <div class="form-group">
                              <label for="birthday_email_message">Mensagem</label>
                              <textarea name="birthday_email_message" id="birthday_email_message" rows="6" class="form-control @error('birthday_email_message') is-invalid @enderror" required>{{ old('birthday_email_message', $birthdayGreeting['message']) }}</textarea>
                              @error('birthday_email_message')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="text-muted">
                              Destino: {{ $user->email ?? 'E-mail não cadastrado' }}
                           </div>
                        </div>
                        <div class="card-footer">
                           <button type="submit" class="btn btn-success">Enviar por e-mail</button>
                        </div>
                     </form>
                  </div>
               </div>

               <div class="col-lg-6">
                  <div class="card card-outline card-success">
                     <div class="card-header">
                        <h3 class="card-title">Parabéns por WhatsApp</h3>
                     </div>
                     <form method="POST" action="{{ route('aluno.birthday.whatsapp.send', $user->id) }}">
                        @csrf
                        <div class="card-body">
                           @if($whatsappInstances->isEmpty())
                           <div class="alert alert-info mb-0">
                              Nenhuma instância ativa do WhatsApp foi cadastrada ainda.
                              <a href="{{ route('whatsapp.instances.index') }}">Cadastrar agora</a>.
                           </div>
                           @else
                           <div class="form-group">
                              <label for="birthday_whatsapp_instance_id">Instância do WhatsApp</label>
                              <select name="birthday_whatsapp_instance_id" id="birthday_whatsapp_instance_id" class="form-control @error('birthday_whatsapp_instance_id') is-invalid @enderror" required>
                                 <option value="">Selecione</option>
                                 @foreach($whatsappInstances as $instance)
                                 <option value="{{ $instance->id }}" {{ (string) old('birthday_whatsapp_instance_id', $whatsappInstances->firstWhere('is_default', true)?->id) === (string) $instance->id ? 'selected' : '' }}>
                                    {{ $instance->name }}{{ $instance->is_default ? ' (Padrão)' : '' }}
                                 </option>
                                 @endforeach
                              </select>
                              @error('birthday_whatsapp_instance_id')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>

                           <div class="form-group">
                              <label for="birthday_whatsapp_message">Mensagem</label>
                              <textarea name="birthday_whatsapp_message" id="birthday_whatsapp_message" rows="6" class="form-control @error('birthday_whatsapp_message') is-invalid @enderror" required>{{ old('birthday_whatsapp_message', $birthdayGreeting['message']) }}</textarea>
                              @error('birthday_whatsapp_message')
                              <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                           </div>
                           <div class="text-muted">
                              Destino: {{ $aluno->telefone ?? 'Telefone não cadastrado' }}
                           </div>
                           @endif
                        </div>
                        @if($whatsappInstances->isNotEmpty())
                        <div class="card-footer">
                           <button type="submit" class="btn btn-success">
                              <i class="fab fa-whatsapp"></i> Enviar por WhatsApp
                           </button>
                        </div>
                        @endif
                     </form>
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </div>
</div>
@stop
