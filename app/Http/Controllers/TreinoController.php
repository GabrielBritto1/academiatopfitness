<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treino;
use App\Models\PlanilhaTreino;

class TreinoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      //
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request)
   {
      $planilha_id = $request->get('planilha_id');
      $planilha = $planilha_id ? PlanilhaTreino::with('aluno')->findOrFail($planilha_id) : null;
      
      return view('treino.create', compact('planilha'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'planilha_id' => 'required|exists:planilha_treinos,id',
         'sigla' => 'required|string|max:10',
         'nome' => 'nullable|string|max:255',
         'dias_semana' => 'nullable|string|max:255',
         'observacoes' => 'nullable|string'
      ]);

      $treino = Treino::create($validated);
      $planilha = PlanilhaTreino::findOrFail($validated['planilha_id']);

      return redirect()->route('planilha-treino.show', $planilha->id)
         ->with('success', 'Treino criado com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $treino = Treino::with(['planilha', 'exercicios' => function($query) {
         $query->orderBy('ordem');
      }])->findOrFail($id);
      
      return view('treino.show', compact('treino'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $treino = Treino::with('planilha')->findOrFail($id);
      
      return view('treino.edit', compact('treino'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $treino = Treino::findOrFail($id);
      $validated = $request->validate([
         'sigla' => 'required|string|max:10',
         'nome' => 'nullable|string|max:255',
         'dias_semana' => 'nullable|string|max:255',
         'observacoes' => 'nullable|string'
      ]);

      $treino->update($validated);

      return redirect()->route('treino.show', $treino->id)
         ->with('success', 'Treino atualizado com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $treino = Treino::findOrFail($id);
      $planilha_id = $treino->planilha_id;
      $treino->delete();

      return redirect()->route('planilha-treino.show', $planilha_id)
         ->with('success', 'Treino excluído com sucesso!');
   }
}
