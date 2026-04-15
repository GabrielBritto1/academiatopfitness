<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TreinoExercicio;
use App\Models\Treino;

class TreinoExercicioController extends Controller
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
      $treino_id = $request->get('treino_id');
      $treino = $treino_id ? Treino::with('planilha.aluno')->findOrFail($treino_id) : null;
      
      return view('treino-exercicio.create', compact('treino'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'treino_id' => 'required|exists:treinos,id',
         'nome' => 'required|string|max:255',
         'series' => 'nullable|string|max:50',
         'repeticoes' => 'nullable|string|max:50',
         'carga' => 'nullable|string|max:50',
         'descanso' => 'nullable|string|max:50',
         'observacao' => 'nullable|string',
         'ordem' => 'nullable|integer|min:0'
      ]);

      // Se não informou ordem, pega a última + 1
      if (!isset($validated['ordem'])) {
         $ultimaOrdem = TreinoExercicio::where('treino_id', $validated['treino_id'])->max('ordem') ?? 0;
         $validated['ordem'] = $ultimaOrdem + 1;
      }

      TreinoExercicio::create($validated);

      return redirect()->route('treino.show', $validated['treino_id'])
         ->with('success', 'Exercício adicionado com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $exercicio = TreinoExercicio::with('treino.planilha.aluno')->findOrFail($id);
      
      return view('treino-exercicio.edit', compact('exercicio'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $exercicio = TreinoExercicio::findOrFail($id);
      $validated = $request->validate([
         'nome' => 'required|string|max:255',
         'series' => 'nullable|string|max:50',
         'repeticoes' => 'nullable|string|max:50',
         'carga' => 'nullable|string|max:50',
         'descanso' => 'nullable|string|max:50',
         'observacao' => 'nullable|string',
         'ordem' => 'nullable|integer|min:0'
      ]);

      $exercicio->update($validated);

      return redirect()->route('treino.show', $exercicio->treino_id)
         ->with('success', 'Exercício atualizado com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $exercicio = TreinoExercicio::findOrFail($id);
      $treino_id = $exercicio->treino_id;
      $exercicio->delete();

      return redirect()->route('treino.show', $treino_id)
         ->with('success', 'Exercício excluído com sucesso!');
   }
}
