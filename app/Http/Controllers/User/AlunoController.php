<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\BillingAlertEmail;
use App\Mail\BirthdayGreetingMail;
use App\Mail\StudentWelcomeMail;
use App\Models\AcademiaUnidade;
use App\Models\AlunoPlanoUnidade;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\Planos;
use App\Models\Aluno;
use App\Models\Role;
use App\Models\User;
use App\Models\WhatsappInstance;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlunoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
      // Controle semi-automático: desativar alunos com última mensalidade paga há mais de 30 dias
      $limite = now()->subDays(30)->startOfDay();

      $alunosAtivos = User::role('aluno')
         ->where('status', true)
         ->get();

      foreach ($alunosAtivos as $alunoUser) {
         $ultimaPagamento = FinancialTransaction::where('kind', 'conta_receber')
            ->where('user_id', $alunoUser->id)
            ->where('status', 'pago')
            ->orderByDesc('paid_at')
            ->orderByDesc('created_at')
            ->first();

         if ($ultimaPagamento) {
            $dataBase = $ultimaPagamento->paid_at ?? $ultimaPagamento->created_at;

            if ($dataBase && $dataBase->lt($limite)) {
               // Depois de 30 dias sem pagamento, desativa o aluno
               $alunoUser->status = false;
               $alunoUser->save();
            }
         }
      }

      // Carregar unidades para o modal de cadastro
      $unidades = AcademiaUnidade::all();

      // Query principal para listar ALUNOS (Users com role aluno)
      $alunosQuery = User::role('aluno')
         ->with([
            'aluno.unidade',  // unidade vem da tabela alunos
         ]);

      // FILTRO NOME
      if ($request->filled('search')) {
         $alunosQuery->where('name', 'like', "%{$request->search}%");
      }

      // O status do aluno é controlado na tabela users.
      if ($request->filled('status')) {
         $alunosQuery->where('status', $request->status);
      }

      $alunos = $alunosQuery->paginate(10);
      $paymentTransactions = $this->resolveEditablePaymentTransactions($alunos->getCollection());

      return view('alunos.index', compact('alunos', 'unidades', 'paymentTransactions'));
   }


   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      return redirect()->route('aluno.index');
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $this->normalizeStudentFormData($request);

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users',
         'cpf' => 'required|digits:11|unique:alunos,cpf',
         'telefone' => 'required|digits_between:10,11',
         'sexo' => 'required|string|max:10',
         'data_nascimento' => 'required|date|before_or_equal:today',
         'unidade_id' => 'nullable|exists:academia_unidades,id',
         'observacoes' => 'nullable|string',
         'foto' => 'nullable|image|max:2048',
      ]);

      $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['email']),
      ]);
      Role::findOrCreate('aluno', config('auth.defaults.guard', 'web'));
      $user->assignRole('aluno');

      $fotoPath = $request->hasFile('foto')
         ? $request->file('foto')->store('alunos', 'public')
         : null;

      $aluno = Aluno::create([
         'user_id' => $user->id,
         'registered_at' => now()->toDateString(),
         'cpf' => $validated['cpf'],
         'telefone' => $validated['telefone'],
         'sexo' => $validated['sexo'],
         'data_nascimento' => $validated['data_nascimento'],
         'unidade_id' => $validated['unidade_id'] ?? null,
         'observacoes' => $validated['observacoes'] ?? null,
         'foto' => $fotoPath,
      ]);

      $response = redirect()->route('aluno.index')->with('success', 'Aluno inserido com sucesso!');

      try {
         Mail::to($user->email)->send(new StudentWelcomeMail(
            studentName: $user->name,
            studentEmail: $user->email,
         ));
      } catch (\Throwable $exception) {
         Log::warning('Falha ao enviar e-mail de boas-vindas do aluno.', [
            'user_id' => $user->id,
            'email' => $user->email,
            'error' => $exception->getMessage(),
         ]);

         $response->with('warning', 'Aluno cadastrado, mas o e-mail de boas-vindas não pôde ser enviado.');
      }

      return $response;
   }

   public function store2(Request $request)
   {
      $validated = $request->validate([
         'user_id' => 'required|exists:users,id',
         'unidades' => 'required|array',
         'planos' => 'required|array',
         'valores' => 'required|array',
         'descontos' => 'required|array',
         'periodicidades' => 'required|array',
         'periodicidades.*' => 'required|in:diario,mensal,semestral,anual',
         'datas_vencimento' => 'required|array',
         'datas_vencimento.*' => 'required|date',
         'forma_pagamento' => 'required|string|max:255',
      ]);

      $user = User::find($validated['user_id']);

      DB::beginTransaction();

      try {
         // Garante que exista ao menos a categoria padrão de receita para mensalidades
         $categoriaMensalidade = FinancialCategory::firstOrCreate(
            ['name' => 'Mensalidade', 'type' => 'receita'],
            ['is_active' => true]
         );

         foreach ($validated['planos'] as $index => $plano_id) {
            $unidade_id = $validated['unidades'][$index] ?? null;
            $descontoPercentual = (float)($validated['descontos'][$index] ?? 0);
            $periodicidade = $validated['periodicidades'][$index] ?? AlunoPlanoUnidade::PERIODICIDADE_MENSAL;
            $dataVencimento = $validated['datas_vencimento'][$index] ?? now()->toDateString();

            // Valor vindo do carrinho (JS já preenche), mas calculamos o total com desconto no backend
            $valorInformado = (float)($validated['valores'][$index] ?? 0);
            $valorTotal = $valorInformado - ($valorInformado * ($descontoPercentual / 100));

            $plano = Planos::findOrFail($plano_id);

            $contrato = AlunoPlanoUnidade::create([
               'user_id' => $user->id,
               'academia_unidade_id' => $unidade_id,
               'plano_id' => $plano_id,
               'valor_inicial' => $valorInformado,
               'valor_total' => $valorTotal,
               'valor_desconto' => $descontoPercentual,
               'forma_pagamento' => $validated['forma_pagamento'],
               'periodicidade' => $periodicidade,
               'data_vencimento' => $dataVencimento,
            ]);

            // A primeira cobrança nasce já com o vencimento configurado no contrato do aluno.
            FinancialTransaction::create([
               'kind' => 'conta_receber',
               'financial_category_id' => $categoriaMensalidade->id,
               'academia_unidade_id' => $unidade_id,
               'user_id' => $user->id,
               'aluno_plano_unidade_id' => $contrato->id,
               'description' => $this->buildRecurringChargeDescription($periodicidade, $plano->name),
               'due_date' => $dataVencimento,
               'paid_at' => null,
               'amount' => $valorInformado,
               'discount' => round($valorInformado * ($descontoPercentual / 100), 2),
               'addition' => 0,
               'amount_paid' => null,
               'payment_method' => $validated['forma_pagamento'],
               'status' => 'pendente',
            ]);
         }

         DB::commit();
      } catch (\Throwable $e) {
         DB::rollBack();
         return redirect()->route('aluno.index')->with('error', 'Não foi possível finalizar o cadastro financeiro dos planos.');
      }

      return redirect()->route('aluno.index')->with('success', 'Planos inseridos com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $user = User::with([
         'aluno.unidade',
         'avaliacoes',
         'planilhas.treinos.exercicios',
         'planilhas.professor',
         'planilhas.unidade',
         'planilhas.plano',
      ])->findOrFail($id);
      $aluno = $user->aluno;
      $planos = $user->planos()->orderBy('name')->get();
      $planPaymentTransactions = $this->resolveOpenPlanPaymentTransactions($user, $planos);
      $avaliacoes = $user->avaliacoes;
      $planilhas = $user->planilhas;
      $paymentTransaction = $this->resolveEditablePaymentTransactionForUser($user);
      $billingAlert = $this->resolveBillingAlertForUser($user);
      $birthdayGreeting = $this->resolveBirthdayGreetingForUser($user);
      $whatsappInstances = $this->resolveActiveWhatsappInstances();

      return view('alunos.show', compact('user', 'aluno', 'planos', 'planPaymentTransactions', 'avaliacoes', 'planilhas', 'paymentTransaction', 'billingAlert', 'birthdayGreeting', 'whatsappInstances'));
   }

   public function photo(Request $request)
   {
      $path = ltrim((string) $request->query('path', ''), '/');

      if ($path === '' || str_contains($path, '..') || ! str_starts_with($path, 'alunos/')) {
         abort(404);
      }

      if (! Storage::disk('public')->exists($path)) {
         abort(404);
      }

      return Storage::disk('public')->response($path);
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $aluno = User::findOrFail($id);
      return view('alunos.edit', compact('aluno'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $user = User::findOrFail($id);
      $this->normalizeStudentFormData($request);

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|email|max:255|unique:users,email,' . $user->id,
         'cpf' => [
            'nullable',
            'digits:11',
            Rule::unique('alunos', 'cpf')->ignore($user->aluno?->id),
         ],
         'telefone' => 'nullable|digits_between:10,11',
         'sexo' => 'nullable|string|max:10',
         'data_nascimento' => 'nullable|date|before_or_equal:today',
         'observacoes' => 'nullable|string',
         'foto' => 'nullable|image|max:2048',
      ]);

      $user->update([
         'name' => $validated['name'],
         'email' => $validated['email'],
      ]);

      $dadosAluno = [
         'cpf' => $validated['cpf'] ?? null,
         'telefone' => $validated['telefone'] ?? null,
         'sexo' => $validated['sexo'] ?? null,
         'data_nascimento' => $validated['data_nascimento'] ?? null,
         'unidade_id' => $user->aluno?->unidade_id,
         'observacoes' => $validated['observacoes'] ?? null,
      ];

      if ($request->hasFile('foto')) {
         if ($user->aluno?->foto) {
            Storage::disk('public')->delete($user->aluno->foto);
         }

         $dadosAluno['foto'] = $request->file('foto')->store('alunos', 'public');
      }

      $user->aluno()->updateOrCreate(
         ['user_id' => $user->id],
         array_merge([
            'registered_at' => $user->aluno?->registered_at ?? now()->toDateString(),
         ], $dadosAluno)
      );

      return redirect()->route('aluno.index')->with('success', 'Aluno editado com sucesso!');
   }

   public function editPlan(string $id, string $contractId)
   {
      $user = User::findOrFail($id);
      $contract = $user->planosContratados()
         ->with(['plano', 'unidade'])
         ->findOrFail($contractId);
      $unidades = AcademiaUnidade::with('planos')
         ->orderBy('nome')
         ->get();

      return view('alunos.planos.edit', compact('user', 'contract', 'unidades'));
   }

   public function updatePlan(Request $request, string $id, string $contractId)
   {
      $user = User::findOrFail($id);
      $contract = $user->planosContratados()->with('plano')->findOrFail($contractId);

      $validated = $request->validate([
         'academia_unidade_id' => 'required|exists:academia_unidades,id',
         'plano_id' => 'required|exists:planos,id',
         'valor_inicial' => 'required|numeric|min:0',
         'valor_desconto' => 'nullable|numeric|min:0|max:100',
         'forma_pagamento' => ['required', 'string', Rule::in(['dinheiro', 'cartao', 'pix', 'boleto'])],
         'periodicidade' => ['required', Rule::in(AlunoPlanoUnidade::PERIODICIDADES)],
         'data_vencimento' => 'required|date',
      ]);

      DB::transaction(function () use ($contract, $validated) {
         $valorInicial = round((float) $validated['valor_inicial'], 2);
         $descontoPercentual = round((float) ($validated['valor_desconto'] ?? 0), 2);
         $valorTotal = round($valorInicial - ($valorInicial * ($descontoPercentual / 100)), 2);

         $contract->update([
            'academia_unidade_id' => $validated['academia_unidade_id'],
            'plano_id' => $validated['plano_id'],
            'valor_inicial' => $valorInicial,
            'valor_total' => $valorTotal,
            'valor_desconto' => $descontoPercentual,
            'forma_pagamento' => $validated['forma_pagamento'],
            'periodicidade' => $validated['periodicidade'],
            'data_vencimento' => $validated['data_vencimento'],
         ]);

         $contract->refresh()->load('plano');
         $this->syncOpenChargesForStudentPlanContract($contract);
      });

      return redirect()
         ->to(route('aluno.show', $user->id) . '#planos')
         ->with('success', 'Plano do aluno atualizado com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      //
   }

   public function toggleStatus(string $id)
   {
      $user = User::findOrFail($id);

      if ($user->status) {
         DB::transaction(function () use ($user) {
            $this->clearStudentPlanData($user);

            $user->update([
               'status' => false,
            ]);
         });

         return redirect()->route('aluno.index')->with('success', 'Aluno cancelado com sucesso!');
      }

      $user->update([
         'status' => true,
      ]);

      return redirect()->route('aluno.index')->with('success', 'Aluno ativado com sucesso!');
   }

   public function sendBillingWhatsappAlert(Request $request, string $id, EvolutionApiService $evolutionApiService)
   {
      $user = User::with('aluno')->findOrFail($id);
      $billingAlert = $this->resolveBillingAlertForUser($user);

      if (! $billingAlert) {
         return redirect()
            ->route('aluno.show', $user->id)
            ->with('error', 'O aluno não possui cobrança próxima do vencimento ou atraso recente para aviso.');
      }

      $validator = validator($request->all(), [
         'whatsapp_instance_id' => 'required|exists:whatsapp_instances,id',
         'message' => 'required|string',
      ]);

      if ($validator->fails()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->withErrors($validator)
            ->withInput()
            ->with('open_whatsapp_tab', true);
      }

      $validated = $validator->validated();

      if (! $this->whatsappInstancesTableExists()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->with('error', 'A estrutura de instâncias do WhatsApp ainda não foi criada neste ambiente.')
            ->with('open_whatsapp_tab', true);
      }

      $instance = WhatsappInstance::query()
         ->whereKey($validated['whatsapp_instance_id'])
         ->where('is_active', true)
         ->first();

      if (! $instance) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->with('error', 'Selecione uma instância ativa do WhatsApp para enviar a mensagem.')
            ->with('open_whatsapp_tab', true);
      }

      $phone = $user->aluno?->telefone;

      if (! $phone) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->with('error', 'O aluno não possui telefone cadastrado para envio por WhatsApp.')
            ->with('open_whatsapp_tab', true);
      }

      try {
         $evolutionApiService->sendText($instance, $phone, $validated['message']);
      } catch (\Throwable $exception) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->with('error', 'Não foi possível enviar a mensagem pela Evolution API.')
            ->with('open_whatsapp_tab', true);
      }

      return redirect()
         ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
         ->with('success', 'Mensagem de cobrança enviada com sucesso pelo WhatsApp.')
         ->with('open_whatsapp_tab', true);
   }

   public function sendBillingEmailAlert(Request $request, string $id)
   {
      $user = User::with('aluno.unidade')->findOrFail($id);
      $billingAlert = $this->resolveBillingAlertForUser($user);

      if (! $billingAlert) {
         return redirect()
            ->route('aluno.show', $user->id)
            ->with('error', 'O aluno não possui cobrança próxima do vencimento ou atraso recente para aviso.');
      }

      $validator = validator($request->all(), [
         'billing_email_message' => 'required|string',
      ]);

      if ($validator->fails()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->withErrors($validator)
            ->withInput()
            ->with('open_billing_tab', true);
      }

      if (! $user->email) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
            ->with('error', 'O aluno não possui e-mail cadastrado para envio.')
            ->with('open_billing_tab', true);
      }

      Mail::to($user->email)->send(new BillingAlertEmail(
         studentName: $user->name,
         subjectLine: $billingAlert['email_subject'],
         messageBody: $validator->validated()['billing_email_message'],
         dueDate: $billingAlert['transaction']->due_date?->format('d/m/Y') ?? '—',
         amount: 'R$ ' . number_format(
            $billingAlert['transaction']->amount - $billingAlert['transaction']->discount + $billingAlert['transaction']->addition,
            2,
            ',',
            '.'
         ),
         transactionDescription: $billingAlert['transaction']->description,
         unitName: $user->aluno?->unidade?->nome,
      ));

      return redirect()
         ->to(route('aluno.show', $user->id) . '#whatsapp-cobranca')
         ->with('success', 'Mensagem de cobrança enviada com sucesso por e-mail.')
         ->with('open_billing_tab', true);
   }

   public function sendBirthdayEmailGreeting(Request $request, string $id)
   {
      $user = User::with('aluno')->findOrFail($id);
      $birthdayGreeting = $this->resolveBirthdayGreetingForUser($user);

      if (! $birthdayGreeting) {
         return redirect()
            ->route('aluno.show', $user->id)
            ->with('error', 'O aluno não está com aniversário hoje.');
      }

      $validator = validator($request->all(), [
         'birthday_email_message' => 'required|string',
      ]);

      if ($validator->fails()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->withErrors($validator)
            ->withInput()
            ->with('open_birthday_tab', true);
      }

      if (! $user->email) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->with('error', 'O aluno não possui e-mail cadastrado.')
            ->with('open_birthday_tab', true);
      }

      Mail::to($user->email)->send(new BirthdayGreetingMail(
         studentName: $user->name,
         messageBody: $validator->validated()['birthday_email_message'],
      ));

      return redirect()
         ->to(route('aluno.show', $user->id) . '#birthday-greetings')
         ->with('success', 'Mensagem de aniversário enviada por e-mail com sucesso.')
         ->with('open_birthday_tab', true);
   }

   public function sendBirthdayWhatsappGreeting(Request $request, string $id, EvolutionApiService $evolutionApiService)
   {
      $user = User::with('aluno')->findOrFail($id);
      $birthdayGreeting = $this->resolveBirthdayGreetingForUser($user);

      if (! $birthdayGreeting) {
         return redirect()
            ->route('aluno.show', $user->id)
            ->with('error', 'O aluno não está com aniversário hoje.');
      }

      $validator = validator($request->all(), [
         'birthday_whatsapp_message' => 'required|string',
         'birthday_whatsapp_instance_id' => 'required|exists:whatsapp_instances,id',
      ]);

      if ($validator->fails()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->withErrors($validator)
            ->withInput()
            ->with('open_birthday_tab', true);
      }

      if (! $this->whatsappInstancesTableExists()) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->with('error', 'A estrutura de instâncias do WhatsApp ainda não foi criada neste ambiente.')
            ->with('open_birthday_tab', true);
      }

      $instance = WhatsappInstance::query()
         ->whereKey($validator->validated()['birthday_whatsapp_instance_id'])
         ->where('is_active', true)
         ->first();

      if (! $instance) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->with('error', 'Selecione uma instância ativa do WhatsApp.')
            ->with('open_birthday_tab', true);
      }

      if (! $user->aluno?->telefone) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->with('error', 'O aluno não possui telefone cadastrado.')
            ->with('open_birthday_tab', true);
      }

      try {
         $evolutionApiService->sendText($instance, $user->aluno->telefone, $validator->validated()['birthday_whatsapp_message']);
      } catch (\Throwable $exception) {
         return redirect()
            ->to(route('aluno.show', $user->id) . '#birthday-greetings')
            ->with('error', 'Não foi possível enviar o parabéns pelo WhatsApp.')
            ->with('open_birthday_tab', true);
      }

      return redirect()
         ->to(route('aluno.show', $user->id) . '#birthday-greetings')
         ->with('success', 'Parabéns enviado por WhatsApp com sucesso.')
         ->with('open_birthday_tab', true);
   }

   private function normalizeStudentFormData(Request $request): void
   {
      $request->merge([
         'cpf' => $this->onlyDigitsOrNull($request->input('cpf')),
         'telefone' => $this->onlyDigitsOrNull($request->input('telefone')),
      ]);
   }

   private function onlyDigitsOrNull(?string $value): ?string
   {
      if ($value === null) {
         return null;
      }

      $digits = preg_replace('/\D+/', '', $value);

      return $digits !== '' ? $digits : null;
   }

   private function resolveEditablePaymentTransactions($users)
   {
      $userIds = collect($users)->pluck('id')->filter()->values();

      if ($userIds->isEmpty()) {
         return collect();
      }

      return FinancialTransaction::where('kind', 'conta_receber')
         ->whereIn('user_id', $userIds)
         ->orderByRaw("CASE WHEN status IN ('pendente', 'vencido') THEN 0 ELSE 1 END")
         ->orderByDesc('due_date')
         ->orderByDesc('created_at')
         ->get()
         ->groupBy('user_id')
         ->map(fn ($transactions) => $transactions->first());
   }

   private function resolveEditablePaymentTransactionForUser(User $user): ?FinancialTransaction
   {
      return FinancialTransaction::where('kind', 'conta_receber')
         ->where('user_id', $user->id)
         ->orderByRaw("CASE WHEN status IN ('pendente', 'vencido') THEN 0 ELSE 1 END")
         ->orderByDesc('due_date')
         ->orderByDesc('created_at')
         ->first();
   }

   private function resolveOpenPlanPaymentTransactions(User $user, $planos)
   {
      $plans = collect($planos);
      $contractIds = $plans
         ->pluck('pivot.id')
         ->filter()
         ->values();
      $showUntilDate = now()->startOfDay()->addDays(7);

      if ($plans->isEmpty()) {
         return collect();
      }

      $openTransactions = FinancialTransaction::query()
         ->where('kind', 'conta_receber')
         ->where('user_id', $user->id)
         ->whereIn('status', ['pendente', 'vencido'])
         ->whereNotNull('due_date')
         ->whereDate('due_date', '<=', $showUntilDate->toDateString())
         ->orderByRaw("CASE WHEN status = 'vencido' THEN 0 ELSE 1 END")
         ->orderBy('due_date')
         ->orderBy('created_at')
         ->get();

      if ($openTransactions->isEmpty()) {
         return collect();
      }

      $planTransactions = $openTransactions
         ->filter(fn (FinancialTransaction $transaction) => $transaction->aluno_plano_unidade_id && $contractIds->contains($transaction->aluno_plano_unidade_id))
         ->groupBy('aluno_plano_unidade_id')
         ->map(fn ($transactions) => $transactions->first());

      $unmatchedPlans = $plans->filter(fn ($plan) => ! $planTransactions->has($plan->pivot->id));

      if ($unmatchedPlans->isEmpty()) {
         return $planTransactions;
      }

      $unmatchedTransactions = $openTransactions->reject(function (FinancialTransaction $transaction) use ($planTransactions) {
         return $transaction->aluno_plano_unidade_id && $planTransactions->has($transaction->aluno_plano_unidade_id);
      })->values();

      if ($unmatchedTransactions->isEmpty()) {
         return $planTransactions;
      }

      foreach ($unmatchedPlans as $plan) {
         $matchingTransactions = $unmatchedTransactions->filter(function (FinancialTransaction $transaction) use ($plan) {
            return $transaction->description
               && stripos($transaction->description, $plan->name) !== false;
         });

         if ($matchingTransactions->count() !== 1) {
            continue;
         }

         $matchedTransaction = $matchingTransactions->first();
         $planTransactions->put($plan->pivot->id, $matchedTransaction);
         $unmatchedTransactions = $unmatchedTransactions->reject(fn (FinancialTransaction $transaction) => $transaction->id === $matchedTransaction->id)->values();
      }

      $remainingPlans = $unmatchedPlans->filter(fn ($plan) => ! $planTransactions->has($plan->pivot->id))->values();

      if ($remainingPlans->count() === 1 && $unmatchedTransactions->count() === 1) {
         $planTransactions->put($remainingPlans->first()->pivot->id, $unmatchedTransactions->first());
      }

      return $planTransactions;
   }

   private function resolveBirthdayGreetingForUser(User $user): ?array
   {
      if (! $user->aluno || ! $user->aluno->isBirthdayToday()) {
         return null;
      }

      return [
         'message' => "Olá {$user->name}! Toda a equipe da Academia Top Fitness deseja um feliz aniversário, com muita saúde, energia e ótimos treinos. Aproveite seu dia! 🎂",
         'email_subject' => 'Feliz aniversário - Academia Top Fitness',
      ];
   }

   private function resolveBillingAlertForUser(User $user): ?array
   {
      $referenceDate = now()->startOfDay();
      $transactions = FinancialTransaction::query()
         ->where('kind', 'conta_receber')
         ->where('user_id', $user->id)
         ->whereIn('status', ['pendente', 'vencido'])
         ->whereNotNull('due_date')
         ->whereBetween('due_date', [
            $referenceDate->copy()->subDays(7)->toDateString(),
            $referenceDate->copy()->addDays(7)->toDateString(),
         ])
         ->get();

      if ($transactions->isEmpty()) {
         return null;
      }

      $transaction = $transactions
         ->sortBy(function (FinancialTransaction $transaction) use ($referenceDate) {
            $dueDate = $transaction->due_date->copy()->startOfDay();
            $priority = $dueDate->lt($referenceDate) ? 0 : 1;
            $distance = str_pad((string) abs($referenceDate->diffInDays($dueDate, false)), 3, '0', STR_PAD_LEFT);

            return "{$priority}-{$distance}-{$dueDate->timestamp}";
         })
         ->first();

      $daysUntilDue = $referenceDate->diffInDays($transaction->due_date->copy()->startOfDay(), false);
      $message = $this->buildBillingWhatsappMessage($user, $transaction, $daysUntilDue);

      return [
         'transaction' => $transaction,
         'days_until_due' => $daysUntilDue,
         'is_overdue' => $daysUntilDue < 0,
         'title' => $daysUntilDue < 0 ? 'Parcela atrasada' : 'Parcela próxima do vencimento',
         'summary' => $daysUntilDue < 0
            ? 'Cobrança vencida recentemente e pronta para aviso por WhatsApp.'
            : 'Cobrança dentro da janela de vencimento para aviso por WhatsApp.',
         'status_badge_class' => $daysUntilDue < 0 ? 'badge-danger' : 'badge-warning',
         'status_label' => $daysUntilDue < 0
            ? 'Atrasada há ' . abs($daysUntilDue) . ' dia(s)'
            : ($daysUntilDue === 0 ? 'Vence hoje' : 'Vence em ' . $daysUntilDue . ' dia(s)'),
         'email_subject' => $daysUntilDue < 0
            ? 'Aviso de cobrança em atraso - Academia Top Fitness'
            : 'Aviso de cobrança - Academia Top Fitness',
         'message' => $message,
      ];
   }

   private function buildBillingWhatsappMessage(User $user, FinancialTransaction $transaction, int $daysUntilDue): string
   {
      $studentName = $user->name;
      $dueDate = $transaction->due_date?->format('d/m/Y') ?? 'data não informada';
      $amount = 'R$ ' . number_format(
         $transaction->amount - $transaction->discount + $transaction->addition,
         2,
         ',',
         '.'
      );

      if ($daysUntilDue < 0) {
         $daysLate = abs($daysUntilDue);

         return "Olá {$studentName}, identificamos que a parcela \"{$transaction->description}\" venceu em {$dueDate} e está atrasada há {$daysLate} dia(s). Valor: {$amount}. Por favor, regularize o pagamento. Se já pagou, desconsidere esta mensagem.";
      }

      if ($daysUntilDue === 0) {
         return "Olá {$studentName}, passando para lembrar que a parcela \"{$transaction->description}\" vence hoje, {$dueDate}. Valor: {$amount}. Se precisar de ajuda, entre em contato com a academia.";
      }

      return "Olá {$studentName}, passando para lembrar que a parcela \"{$transaction->description}\" vence em {$dueDate}, daqui a {$daysUntilDue} dia(s). Valor: {$amount}. Se precisar de ajuda, entre em contato com a academia.";
   }

   private function whatsappInstancesTableExists(): bool
   {
      return Schema::hasTable('whatsapp_instances');
   }

   private function resolveActiveWhatsappInstances()
   {
      if (! $this->whatsappInstancesTableExists()) {
         return collect();
      }

      return WhatsappInstance::query()
         ->where('is_active', true)
         ->orderByDesc('is_default')
         ->orderBy('name')
         ->get();
   }

   private function buildRecurringChargeDescription(string $periodicidade, string $planoNome): string
   {
      $prefixo = match ($periodicidade) {
         AlunoPlanoUnidade::PERIODICIDADE_DIARIA => 'Diaria',
         AlunoPlanoUnidade::PERIODICIDADE_SEMESTRAL => 'Semestralidade',
         AlunoPlanoUnidade::PERIODICIDADE_ANUAL => 'Anuidade',
         default => 'Mensalidade',
      };

      return $prefixo . ' - ' . $planoNome;
   }

   private function syncOpenChargesForStudentPlanContract(AlunoPlanoUnidade $contract): void
   {
      $openTransactions = $contract->financialTransactions()
         ->where('kind', 'conta_receber')
         ->whereIn('status', ['pendente', 'vencido'])
         ->orderBy('due_date')
         ->orderBy('created_at')
         ->get();

      if ($openTransactions->isEmpty()) {
         return;
      }

      $currentDueDate = $contract->dueDateAnchor();
      $description = $this->buildRecurringChargeDescription(
         $contract->periodicidade,
         $contract->plano?->name ?? 'Plano'
      );

      foreach ($openTransactions as $transaction) {
         $transaction->update([
            'academia_unidade_id' => $contract->academia_unidade_id,
            'description' => $description,
            'due_date' => $currentDueDate->toDateString(),
            'amount' => $contract->valor_inicial,
            'discount' => $contract->monetaryDiscount(),
            'payment_method' => $contract->forma_pagamento,
         ]);

         $currentDueDate = $contract->nextDueDateFrom($currentDueDate);
      }
   }

   private function clearStudentPlanData(User $user): void
   {
      $contracts = $user->planosContratados()->get();
      $contractIds = $contracts->pluck('id');

      if ($contractIds->isNotEmpty()) {
         FinancialTransaction::query()
            ->whereIn('aluno_plano_unidade_id', $contractIds)
            ->where('kind', 'conta_receber')
            ->whereIn('status', ['pendente', 'vencido'])
            ->update([
               'status' => 'cancelado',
            ]);
      }

      FinancialTransaction::query()
         ->where('user_id', $user->id)
         ->where('kind', 'conta_receber')
         ->whereNull('aluno_plano_unidade_id')
         ->whereIn('status', ['pendente', 'vencido'])
         ->where(function ($query) {
            $query
               ->where('description', 'like', 'Mensalidade%')
               ->orWhere('description', 'like', 'Semestralidade%')
               ->orWhere('description', 'like', 'Anuidade%')
               ->orWhere('description', 'like', 'Diaria%');
         })
         ->update([
            'status' => 'cancelado',
         ]);

      $contracts->each(function (AlunoPlanoUnidade $contract) {
         $hasOpenTransactions = FinancialTransaction::query()
            ->where('aluno_plano_unidade_id', $contract->id)
            ->whereNotIn('status', ['pago', 'cancelado'])
            ->exists();

         if (! $hasOpenTransactions) {
            $contract->delete();
         }
      });
   }
}
