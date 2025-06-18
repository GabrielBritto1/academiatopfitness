<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use App\Models\Planos;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlunoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
      $unidades = AcademiaUnidade::all();
      $unidades->each(function ($unidade) {
         $unidade->planos = $unidade->planos()->get();
      });
      $roles = Role::all();
      $alunosQuery = User::whereHas('roles', function ($query) {
         $query->where('name', 'aluno');
      })->with(['planos']);

      if ($request->filled('search')) {
         $alunosQuery->where('name', 'like', '%' . $request->input('search') . '%');
      }
      if ($request->has('search')) {
         $alunosQuery->where('status', $request->input('status'));
      }
      $alunos = $alunosQuery->paginate(10);

      return view('alunos.index', compact('alunos', 'roles', 'unidades'));
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
      ]);

      $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['email']),
      ]);
      $user->roles()->attach(2);

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

      foreach ($validated['planos'] as $index => $plano_id) {
         $unidade_id = $validated['unidades'][$index];
         $desconto = $validated['descontos'][$index] ?? 0;

         // Busca o plano para pegar os valores
         $plano = Planos::find($plano_id);

         // Verifica se o dia atual é maior que o dia de vencimento do plano
         $diaVencimento = (int) $plano->dia_vencimento;
         $diaAtual = now()->day;

         if ($diaVencimento && $diaAtual > $diaVencimento) {
            $valor = $plano->preco_pos_vencimento;
         } else {
            $valor = $plano->preco_pre_vencimento;
         }

         $valor_total = $valor - ($valor * ($desconto / 100));

         $user->planos()->attach($plano_id, [
            'academia_unidade_id' => $unidade_id,
            'valor_inicial' => $valor,
            'valor_total' => $valor_total,
            'valor_desconto' => $desconto,
            'forma_pagamento' => $validated['forma_pagamento'],
         ]);
      }

      return redirect()->route('aluno.index')->with('success', 'Planos inseridos com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $aluno = User::findOrFail($id);
      return view('alunos.show', compact('aluno'));
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
