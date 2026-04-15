<?php

namespace App\Http\Controllers;

use App\Models\AcademiaUnidade;
use App\Models\PlanilhaTreino;
use App\Models\Aluno;
use App\Models\User;
use App\Models\Planos;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PlanilhaTreinoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $planilhas = PlanilhaTreino::where('is_padrao', false)
         ->with(['aluno', 'professor', 'unidade', 'plano'])
         ->get();
      $planilhasPadrao = PlanilhaTreino::where('is_padrao', true)
         ->with(['professor', 'unidade', 'plano', 'treinos.exercicios'])
         ->get();
      return view('planilha-treino.index', compact('planilhas', 'planilhasPadrao'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request)
   {
      $aluno_id = $request->get('aluno_id');
      $aluno = $aluno_id ? User::findOrFail($aluno_id) : null;
      $professores = User::role('professor')->get();
      $unidades = AcademiaUnidade::all();
      $planos = Planos::all();
      $planilhasPadrao = PlanilhaTreino::where('is_padrao', true)
         ->with(['professor', 'unidade', 'treinos.exercicios'])
         ->get();
      
      return view('planilha-treino.create', compact('aluno', 'professores', 'unidades', 'planos', 'planilhasPadrao'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      // Se está duplicando uma planilha padrão
      if ($request->has('planilha_padrao_id')) {
         return $this->duplicarPlanilhaPadrao($request);
      }

      $isPadrao = $request->boolean('is_padrao');

      $validated = $request->validate([
         'is_padrao' => 'nullable|boolean',
         'nome' => ($isPadrao ? 'required' : 'nullable') . '|string|max:255',
         'aluno_id' => ($isPadrao ? 'nullable' : 'required') . '|exists:users,id',
         'professor_id' => 'required|exists:users,id',
         'unidade_id' => 'required|exists:academia_unidades,id',
         'plano_id' => 'nullable|exists:planos,id',
         'observacoes' => 'nullable|string',
      ]);

      $validated['is_padrao'] = $isPadrao;

      if ($isPadrao) {
         $validated['aluno_id'] = null;
      }

      $planilha = PlanilhaTreino::create($validated);

      if ($isPadrao) {
         return redirect()->route('planilha-treino.index')
            ->with('success', 'Planilha padrão criada com sucesso!');
      }

      return redirect()->route('planilha-treino.show', $planilha->id)
         ->with('success', 'Planilha de treino criada com sucesso!');
   }

   /**
    * Duplica uma planilha padrão para um aluno
    */
   private function duplicarPlanilhaPadrao(Request $request)
   {
      $validated = $request->validate([
         'planilha_padrao_id' => 'required|exists:planilha_treinos,id',
         'aluno_id' => 'required|exists:users,id',
         'professor_id' => 'required|exists:users,id',
         'unidade_id' => 'required|exists:academia_unidades,id',
         'plano_id' => 'nullable|exists:planos,id',
      ]);

      $planilhaPadrao = PlanilhaTreino::with(['treinos.exercicios'])->findOrFail($validated['planilha_padrao_id']);

      // Criar nova planilha para o aluno
      $novaPlanilha = PlanilhaTreino::create([
         'is_padrao' => false,
         'aluno_id' => $validated['aluno_id'],
         'professor_id' => $validated['professor_id'],
         'unidade_id' => $validated['unidade_id'],
         'plano_id' => $validated['plano_id'] ?? null,
         'observacoes' => $planilhaPadrao->observacoes,
      ]);

      // Duplicar treinos e exercícios
      foreach ($planilhaPadrao->treinos as $treinoPadrao) {
         $novoTreino = $novaPlanilha->treinos()->create([
            'sigla' => $treinoPadrao->sigla,
            'nome' => $treinoPadrao->nome,
            'dias_semana' => $treinoPadrao->dias_semana,
            'observacoes' => $treinoPadrao->observacoes,
         ]);

         foreach ($treinoPadrao->exercicios as $exercicioPadrao) {
            $novoTreino->exercicios()->create([
               'nome' => $exercicioPadrao->nome,
               'series' => $exercicioPadrao->series,
               'repeticoes' => $exercicioPadrao->repeticoes,
               'carga' => $exercicioPadrao->carga,
               'descanso' => $exercicioPadrao->descanso,
               'observacao' => $exercicioPadrao->observacao,
               'ordem' => $exercicioPadrao->ordem,
            ]);
         }
      }

      return redirect()->route('planilha-treino.show', $novaPlanilha->id)
         ->with('success', 'Planilha padrão aplicada ao aluno com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $planilha = PlanilhaTreino::with(['aluno', 'professor', 'unidade', 'plano', 'treinos.exercicios'])
         ->findOrFail($id);
      
      return view('planilha-treino.show', compact('planilha'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $planilha = PlanilhaTreino::findOrFail($id);
      $professores = User::role('professor')->get();
      $unidades = AcademiaUnidade::all();
      $planos = Planos::all();
      $alunos = $planilha->is_padrao ? null : User::role('aluno')->get();
      return view('planilha-treino.edit', compact('planilha', 'professores', 'unidades', 'planos', 'alunos'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $planilha = PlanilhaTreino::findOrFail($id);
      
      $rules = [
         'unidade_id' => 'required|exists:academia_unidades,id',
         'professor_id' => 'required|exists:users,id',
         'plano_id' => 'nullable|exists:planos,id',
         'observacoes' => 'nullable|string',
      ];

      if ($planilha->is_padrao) {
         $rules['nome'] = 'required|string|max:255';
      } else {
         $rules['aluno_id'] = 'required|exists:users,id';
      }

      $validated = $request->validate($rules);
      $planilha->update($validated);
      
      return redirect()->route('planilha-treino.show', $planilha->id)->with('success', 'Planilha editada com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $planilha = PlanilhaTreino::findOrFail($id);
      $is_padrao = $planilha->is_padrao;
      $aluno_id = $planilha->aluno_id;
      $planilha->delete();

      if ($is_padrao) {
         return redirect()->route('planilha-treino.index')
            ->with('success', 'Planilha padrão excluída com sucesso!');
      }

      if ($aluno_id) {
         return redirect()->route('aluno.show', $aluno_id)
            ->with('success', 'Planilha de treino excluída com sucesso!');
      }

      return redirect()->route('planilha-treino.index')
         ->with('success', 'Planilha excluída com sucesso!');
   }
   public function planilhaTreinoPdf($aluno_id)
   {
      $user = User::with(['planilhas.treinos.exercicios'])->findOrFail($aluno_id);

      $pdf = Pdf::loadView('planilha-treino.planilha_treino_pdf', ['aluno' => $user])
         ->setPaper('a4');

      return $pdf->stream('treino-' . $user->name . '.pdf');
   }
}
