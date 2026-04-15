<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\AlunoPlanoUnidade;
use App\Models\AcademiaUnidade;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialTransactionController extends Controller
{
   /**
    * Display a listing of transactions (Caixa/Fluxo de Caixa).
    */
   public function index(Request $request)
   {
      $query = FinancialTransaction::with(['category', 'unidade', 'user']);

      // Filtros
      if ($request->filled('kind')) {
         $query->where('kind', $request->kind);
      }

      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      if ($request->filled('unidade_id')) {
         $query->where('academia_unidade_id', $request->unidade_id);
      }

      if ($request->filled('date_from')) {
         $query->whereDate('created_at', '>=', $request->date_from);
      }

      if ($request->filled('date_to')) {
         $query->whereDate('created_at', '<=', $request->date_to);
      }

      $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

      $unidades = AcademiaUnidade::all();

      // Totais
      $receitas = FinancialTransaction::where('kind', 'conta_receber')
         ->where('status', 'pago')
         ->get();

      $despesas = FinancialTransaction::where('kind', 'conta_pagar')
         ->where('status', 'pago')
         ->get();

      $totalReceitas = $receitas->sum(function ($transaction) {
         return $transaction->amount_paid ?? ($transaction->amount - $transaction->discount + $transaction->addition);
      });

      $totalDespesas = $despesas->sum(function ($transaction) {
         return $transaction->amount_paid ?? ($transaction->amount - $transaction->discount + $transaction->addition);
      });

      $saldo = $totalReceitas - $totalDespesas;

      return view('financeiro.transacoes.index', compact('transactions', 'unidades', 'totalReceitas', 'totalDespesas', 'saldo'));
   }

   /**
    * Show the form for creating a new transaction.
    */
   public function create(Request $request)
   {
      $kind = $request->get('kind', 'conta_receber'); // conta_receber ou conta_pagar

      $categories = FinancialCategory::where('type', $kind === 'conta_receber' ? 'receita' : 'despesa')
         ->where('is_active', true)
         ->orderBy('name')
         ->get();

      $unidades = AcademiaUnidade::all();
      $alunos = User::role('aluno')->get();

      return view('financeiro.transacoes.create', compact('kind', 'categories', 'unidades', 'alunos'));
   }

   /**
    * Store a newly created transaction.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'kind' => 'required|in:conta_receber,conta_pagar',
         'financial_category_id' => 'nullable|exists:financial_categories,id',
         'academia_unidade_id' => 'nullable|exists:academia_unidades,id',
         'user_id' => 'nullable|exists:users,id',
         'description' => 'required|string|max:255',
         'due_date' => 'nullable|date',
         'paid_at' => 'nullable|date',
         'amount' => 'required|numeric|min:0',
         'discount' => 'nullable|numeric|min:0',
         'addition' => 'nullable|numeric|min:0',
         'amount_paid' => 'nullable|numeric|min:0',
         'payment_method' => 'nullable|string|max:255',
         'status' => 'required|in:pendente,pago,vencido,cancelado',
      ]);

      $transaction = FinancialTransaction::create($validated);
      $this->activateStudentWhenPaymentIsSettled($transaction);
      $this->createNextRecurringChargeIfNeeded($transaction->fresh('contrato.plano'));

      $route = $validated['kind'] === 'conta_receber'
         ? 'financeiro.contas-receber.index'
         : 'financeiro.contas-pagar.index';

      return redirect()->route($route)->with('success', 'Transação criada com sucesso!');
   }

   /**
    * Display the specified transaction.
    */
   public function show(string $id)
   {
      $transaction = FinancialTransaction::with(['category', 'unidade', 'user'])->findOrFail($id);
      return view('financeiro.transacoes.show', compact('transaction'));
   }

   /**
    * Show the form for editing the specified transaction.
    */
   public function edit(string $id)
   {
      $transaction = FinancialTransaction::findOrFail($id);

      $categories = FinancialCategory::where('type', $transaction->kind === 'conta_receber' ? 'receita' : 'despesa')
         ->where('is_active', true)
         ->orderBy('name')
         ->get();

      $unidades = AcademiaUnidade::all();
      $alunos = User::role('aluno')->get();

      return view('financeiro.transacoes.edit', compact('transaction', 'categories', 'unidades', 'alunos'));
   }

   /**
    * Update the specified transaction.
    */
   public function update(Request $request, string $id)
   {
      $transaction = FinancialTransaction::findOrFail($id);

      $validated = $request->validate([
         'kind' => 'required|in:conta_receber,conta_pagar',
         'financial_category_id' => 'nullable|exists:financial_categories,id',
         'academia_unidade_id' => 'nullable|exists:academia_unidades,id',
         'user_id' => 'nullable|exists:users,id',
         'description' => 'required|string|max:255',
         'due_date' => 'nullable|date',
         'paid_at' => 'nullable|date',
         'amount' => 'required|numeric|min:0',
         'discount' => 'nullable|numeric|min:0',
         'addition' => 'nullable|numeric|min:0',
         'amount_paid' => 'nullable|numeric|min:0',
         'payment_method' => 'nullable|string|max:255',
         'status' => 'required|in:pendente,pago,vencido,cancelado',
      ]);

      $transaction->update($validated);
      $updatedTransaction = $transaction->fresh('contrato.plano');
      $this->activateStudentWhenPaymentIsSettled($updatedTransaction);
      $this->createNextRecurringChargeIfNeeded($updatedTransaction);

      $route = $validated['kind'] === 'conta_receber'
         ? 'financeiro.contas-receber.index'
         : 'financeiro.contas-pagar.index';

      return redirect()->route($route)->with('success', 'Transação atualizada com sucesso!');
   }

   /**
    * Remove the specified transaction.
    */
   public function destroy(string $id)
   {
      $transaction = FinancialTransaction::findOrFail($id);
      $kind = $transaction->kind;
      $transaction->delete();

      $route = $kind === 'conta_receber'
         ? 'financeiro.contas-receber.index'
         : 'financeiro.contas-pagar.index';

      return redirect()->route($route)->with('success', 'Transação deletada com sucesso!');
   }

   /**
    * Marcar transação como paga.
    */
   public function markAsPaid(Request $request, string $id)
   {
      $transaction = FinancialTransaction::findOrFail($id);

      $validated = $request->validate([
         'paid_at' => 'nullable|date',
         'amount_paid' => 'nullable|numeric|min:0',
         'payment_method' => 'nullable|string|max:255',
      ]);

      $transaction->update([
         'status' => 'pago',
         'paid_at' => $validated['paid_at'] ?? now(),
         'amount_paid' => $validated['amount_paid'] ?? ($transaction->amount - $transaction->discount + $transaction->addition),
         'payment_method' => $validated['payment_method'] ?? $transaction->payment_method,
      ]);
      $updatedTransaction = $transaction->fresh('contrato.plano');
      $this->activateStudentWhenPaymentIsSettled($updatedTransaction);
      $this->createNextRecurringChargeIfNeeded($updatedTransaction);

      return redirect()->back()->with('success', 'Transação marcada como paga!');
   }

   /**
    * Listar contas a receber.
    */
   public function contasReceber(Request $request)
   {
      $query = FinancialTransaction::where('kind', 'conta_receber')
         ->orderBy('id', 'desc')
         ->with(['category', 'unidade', 'user']);

      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      if ($request->filled('unidade_id')) {
         $query->where('academia_unidade_id', $request->unidade_id);
      }

      $transactions = $query->orderBy('due_date', 'asc')->paginate(20);
      $unidades = AcademiaUnidade::all();

      return view('financeiro.contas-receber.index', compact('transactions', 'unidades'));
   }

   /**
    * Listar contas a pagar.
    */
   public function contasPagar(Request $request)
   {
      $query = FinancialTransaction::where('kind', 'conta_pagar')
         ->with(['category', 'unidade', 'user']);

      if ($request->filled('status')) {
         $query->where('status', $request->status);
      }

      if ($request->filled('unidade_id')) {
         $query->where('academia_unidade_id', $request->unidade_id);
      }

      $transactions = $query->orderBy('due_date', 'asc')->paginate(20);
      $unidades = AcademiaUnidade::all();

      return view('financeiro.contas-pagar.index', compact('transactions', 'unidades'));
   }

   private function activateStudentWhenPaymentIsSettled(FinancialTransaction $transaction): void
   {
      if (
         $transaction->kind !== 'conta_receber'
         || $transaction->status !== 'pago'
         || ! $transaction->user_id
      ) {
         return;
      }

      $user = $transaction->user()->first();

      if (! $user || ! $user->hasRole('aluno') || $user->status) {
         return;
      }

      $user->update([
         'status' => true,
      ]);
   }

   private function createNextRecurringChargeIfNeeded(FinancialTransaction $transaction): void
   {
      if (
         $transaction->kind !== 'conta_receber'
         || $transaction->status !== 'pago'
         || ! $transaction->aluno_plano_unidade_id
      ) {
         return;
      }

      /** @var AlunoPlanoUnidade|null $contrato */
      $contrato = $transaction->contrato;

      if (! $contrato) {
         return;
      }

      $baseDueDate = $transaction->due_date
         ? $transaction->due_date->copy()
         : $contrato->dueDateAnchor();

      $nextDueDate = $contrato->nextDueDateFrom($baseDueDate);

      $alreadyExists = FinancialTransaction::where('kind', 'conta_receber')
         ->where('aluno_plano_unidade_id', $contrato->id)
         ->whereDate('due_date', $nextDueDate->toDateString())
         ->exists();

      if ($alreadyExists) {
         return;
      }

      FinancialTransaction::create([
         'kind' => 'conta_receber',
         'financial_category_id' => $transaction->financial_category_id,
         'academia_unidade_id' => $contrato->academia_unidade_id,
         'user_id' => $contrato->user_id,
         'aluno_plano_unidade_id' => $contrato->id,
         'description' => $this->buildRecurringChargeDescription($contrato),
         'due_date' => $nextDueDate->toDateString(),
         'amount' => $contrato->valor_inicial,
         'discount' => $contrato->monetaryDiscount(),
         'addition' => 0,
         'amount_paid' => null,
         'payment_method' => $contrato->forma_pagamento,
         'status' => 'pendente',
      ]);
   }

   private function buildRecurringChargeDescription(AlunoPlanoUnidade $contrato): string
   {
      $prefixo = match ($contrato->periodicidade) {
         AlunoPlanoUnidade::PERIODICIDADE_DIARIA => 'Diaria',
         AlunoPlanoUnidade::PERIODICIDADE_SEMESTRAL => 'Semestralidade',
         AlunoPlanoUnidade::PERIODICIDADE_ANUAL => 'Anuidade',
         default => 'Mensalidade',
      };

      return $prefixo . ' - ' . ($contrato->plano?->name ?? 'Plano');
   }
}
