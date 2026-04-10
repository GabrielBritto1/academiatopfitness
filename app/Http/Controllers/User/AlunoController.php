<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use App\Models\Planos;
use App\Models\Role;
use App\Models\Aluno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
      // Controle semi-automático: desativar alunos com última mensalidade paga há mais de 30 dias
      $limite = now()->subDays(30)->startOfDay();

      $alunosAtivos = User::whereHas('roles', function ($q) {
         $q->where('name', 'aluno');
      })
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
      $alunosQuery = User::whereHas(
         'roles',
         fn($q) =>
         $q->where('name', 'aluno')
      )
         ->with([
            'aluno.unidade',  // unidade vem da tabela alunos
         ]);

      // FILTRO NOME
      if ($request->filled('search')) {
         $alunosQuery->where('name', 'like', "%{$request->search}%");
      }

      // FILTRO STATUS — mas o status está em Aluno, não em User
      if ($request->filled('status')) {
         $alunosQuery->whereHas('aluno', function ($q) use ($request) {
            $q->where('status', $request->status);
         });
      }

      $alunos = $alunosQuery->paginate(10);

      return view('alunos.index', compact('alunos', 'unidades'));
   }


   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      return view('aluno.index');
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users',
         'cpf' => 'required|string|max:11',
         'telefone' => 'required|string|max:15',
         'sexo' => 'required|string|max:10',
         'idade' => 'required|integer',
         'unidade_id' => 'nullable|exists:academia_unidades,id',
         'observacoes' => 'nullable|string',
         'foto' => 'nullable|image|max:2048',
      ]);

      $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['email']),
      ]);
      $user->roles()->attach(2);

      $aluno = Aluno::create([
         'user_id' => $user->id,
         'registered_at' => now()->toDateString(),
         'cpf' => $validated['cpf'],
         'telefone' => $validated['telefone'],
         'sexo' => $validated['sexo'],
         'idade' => $validated['idade'],
         'unidade_id' => $validated['unidade_id'],
         'observacoes' => $validated['observacoes'],
         'foto' => $nomeDaFoto ?? null,
      ]);

      return redirect()->route('aluno.index')->with('success', 'Aluno inserido com sucesso!');
   }

   public function store2(Request $request)
   {
      $validated = $request->validate([
         'user_id' => 'required|exists:users,id',
         'unidades' => 'required|array',
         'planos' => 'required|array',
         'valores' => 'required|array',
         'descontos' => 'required|array',
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

            // Valor vindo do carrinho (JS já preenche), mas calculamos o total com desconto no backend
            $valorInformado = (float)($validated['valores'][$index] ?? 0);
            $valorTotal = $valorInformado - ($valorInformado * ($descontoPercentual / 100));

            $plano = Planos::findOrFail($plano_id);

            // Inserir no pivot (aluno_plano_unidade) e capturar o ID para referenciar no financeiro
            $alunoPlanoUnidadeId = DB::table('aluno_plano_unidade')->insertGetId([
               'user_id' => $user->id,
               'academia_unidade_id' => $unidade_id,
               'plano_id' => $plano_id,
               'valor_inicial' => $valorInformado,
               'valor_total' => $valorTotal,
               'valor_desconto' => $descontoPercentual,
               'forma_pagamento' => $validated['forma_pagamento'],
               'created_at' => now(),
               'updated_at' => now(),
            ]);

            // Automatização: cria conta a receber para este plano
            FinancialTransaction::create([
               'kind' => 'conta_receber',
               'financial_category_id' => $categoriaMensalidade->id,
               'academia_unidade_id' => $unidade_id,
               'user_id' => $user->id,
               'aluno_plano_unidade_id' => $alunoPlanoUnidadeId,
               'description' => 'Mensalidade - ' . $plano->name,
               'due_date' => now()->toDateString(),
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
      $user = User::findOrFail($id);
      $aluno = $user->aluno;
      $planos = Planos::all();
      $avaliacoes = $user->avaliacoes;
      $planilhas = $user->planilhas()->with(['treinos.exercicios', 'professor', 'unidade', 'plano'])->get();
      return view('alunos.show', compact('user', 'aluno', 'planos', 'avaliacoes', 'planilhas'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $aluno = User::findOrFail($id);
      $unidades = AcademiaUnidade::all();
      return view('alunos.edit', compact('aluno', 'unidades'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $user = User::findOrFail($id);

      $validated = $request->validate([
         'name' => 'required',
         'email' => 'required|email',
      ]);

      $user->update([
         'name' => $validated['name'],
         'email' => $validated['email'],
      ]);

      return redirect()->route('aluno.index')->with('success', 'Aluno editado com sucesso!');
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
      $user->status = !$user->status;
      $user->save();

      return redirect()->route('aluno.index')->with('success', 'Status do aluno atualizado com sucesso!');
   }
}
