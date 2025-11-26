<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $alunos = User::whereHas('roles', function ($query) {
         $query->where('name', 'aluno');
      })->get();
      return view('avaliacao.index', compact('alunos'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      $alunos = User::whereHas('roles', function ($query) {
         $query->where('name', 'aluno');
      })->get();
      return view('avaliacao.create', compact('alunos'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'aluno_id' => 'required|exists:users,id',
         'professor_id' => 'required|exists:users,id',
         'peso' => 'nullable|string',
         'altura' => 'nullable|string',
         'imc' => 'nullable|string',
         'gordura' => 'nullable|string',
         'massa_muscular' => 'nullable|string',
         'circunferencia_cintura' => 'nullable|string',
         'circunferencia_quadril' => 'nullable|string',
         'circunferencia_braco_relaxado' => 'nullable|string',
         'circunferencia_braco_contraido' => 'nullable|string',
         'circunferencia_peito' => 'nullable|string',
         'circunferencia_coxa' => 'nullable|string',
         'circunferencia_panturrilha' => 'nullable|string',
         'observacoes' => 'nullable|string',
      ]);

      Avaliacao::create([
         'aluno_id' => $validated['aluno_id'],
         'professor_id' => $validated['professor_id'],
         'peso' => $validated['peso'],
         'altura' => $validated['altura'],
         'imc' => $validated['imc'],
         'gordura' => $validated['gordura'],
         'massa_muscular' => $validated['massa_muscular'],
         'circunferencia_cintura' => $validated['circunferencia_cintura'],
         'circunferencia_quadril' => $validated['circunferencia_quadril'],
         'circunferencia_braco_relaxado' => $validated['circunferencia_braco_relaxado'],
         'circunferencia_braco_contraido' => $validated['circunferencia_braco_contraido'],
         'circunferencia_peito' => $validated['circunferencia_peito'],
         'circunferencia_coxa' => $validated['circunferencia_coxa'],
         'circunferencia_panturrilha' => $validated['circunferencia_panturrilha'],
         'observacoes' => $validated['observacoes'],
      ]);

      return redirect()->route('avaliacao.index')->with('success', 'Avaliação feita com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $aluno = User::findOrFail($id);
      $avaliacoes = Avaliacao::where('aluno_id', $id)->get();
      return view('avaliacao.show', compact('avaliacoes', 'aluno'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $id = Avaliacao::findOrFail($id);
      $id->delete();
      return redirect()->route('avaliacao.index')->with('success', 'Avaliação removida com sucesso!');
   }
   /**
    * Generate PDF for the specified evaluation.
    */
   public function avaliacaoPdf(string $id)
   {
      $aluno = User::findOrFail($id);
      $avaliacoes = Avaliacao::where('aluno_id', $id)->get();
      $pdf = Pdf::loadView('avaliacao.avaliacao_pdf', compact('avaliacoes', 'aluno'))->setPaper('A4');
      return $pdf->stream('avaliacao.pdf');
   }
   /**
    * Generate chart for the specified evaluation.
    */
   public function avaliacaoGrafico(string $id)
   {
      $aluno = User::findOrFail($id);
      $avaliacoes = Avaliacao::where('aluno_id', $id)
         ->orderBy('created_at', 'asc')
         ->get();
      $labels = $avaliacoes->pluck('created_at')->map(function ($date) {
         return $date->format('d/m/Y');
      });
      $pesoData = $avaliacoes->pluck('peso');
      $alturaData = $avaliacoes->pluck('altura');
      $imcData = $avaliacoes->pluck('imc');
      $gorduraData = $avaliacoes->pluck('gordura');
      $massaMuscularData = $avaliacoes->pluck('massa_muscular');
      $circunferenciaCinturaData = $avaliacoes->pluck('circunferencia_cintura');
      $circunferenciaQuadrilData = $avaliacoes->pluck('circunferencia_quadril');
      $circunferenciaBracoRelaxadoData = $avaliacoes->pluck('circunferencia_braco_relaxado');
      $circunferenciaBracoContraidoData = $avaliacoes->pluck('circunferencia_braco_contraido');
      $circunferenciaPeitoData = $avaliacoes->pluck('circunferencia_peito');
      $circunferenciaCoxaData = $avaliacoes->pluck('circunferencia_coxa');
      $circunferenciaPanturrilhaData = $avaliacoes->pluck('circunferencia_panturrilha');
      return view('avaliacao.avaliacao_grafico', [
         'aluno' => $aluno,
         'labels' => $labels,
         'pesoData' => $pesoData,
         'alturaData' => $alturaData,
         'imcData' => $imcData,
         'gorduraData' => $gorduraData,
         'massaMuscularData' => $massaMuscularData,
         'circunferenciaCinturaData' => $circunferenciaCinturaData,
         'circunferenciaQuadrilData' => $circunferenciaQuadrilData,
         'circunferenciaBracoRelaxadoData' => $circunferenciaBracoRelaxadoData,
         'circunferenciaBracoContraidoData' => $circunferenciaBracoContraidoData,
         'circunferenciaPeitoData' => $circunferenciaPeitoData,
         'circunferenciaCoxaData' => $circunferenciaCoxaData,
         'circunferenciaPanturrilhaData' => $circunferenciaPanturrilhaData
      ]);
   }
}
