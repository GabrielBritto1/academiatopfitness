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
      $alunos = User::role('aluno')->get();
      return view('avaliacao.index', compact('alunos'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      $alunos = User::role('aluno')->get();
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
         'massa_muscular' => 'nullable|string',
         'gordura' => 'nullable|string',

         // Perimetrias
         'torax' => 'nullable|string',
         'cintura' => 'nullable|string',
         'abdomen_medida' => 'nullable|string',
         'quadril' => 'nullable|string',
         'braco_relaxado_esquerdo' => 'nullable|string',
         'braco_relaxado_direito' => 'nullable|string',
         'braco_contraido_esquerdo' => 'nullable|string',
         'braco_contraido_direito' => 'nullable|string',
         'coxa_medial' => 'nullable|string',
         'panturrilha' => 'nullable|string',

         // Dobras
         'peito' => 'nullable|string',
         'triceps' => 'nullable|string',
         'subescapular' => 'nullable|string',
         'axilar_media' => 'nullable|string',
         'supra_iliaca' => 'nullable|string',
         'abdomen_dobra' => 'nullable|string',
         'coxa_dobra' => 'nullable|string',

         'protocolo' => 'nullable|string',
         'sexo_avaliacao' => 'nullable|string',

         'observacoes' => 'nullable|string',
      ]);

      Avaliacao::create([
         'aluno_id' => $validated['aluno_id'],
         'professor_id' => $validated['professor_id'],

         'peso' => $validated['peso'],
         'altura' => $validated['altura'],
         'imc' => $validated['imc'],
         'massa_muscular' => $validated['massa_muscular'],
         'gordura' => $validated['gordura'],

         // Perimetrias
         'torax' => $validated['torax'],
         'cintura' => $validated['cintura'],
         'abdomen_medida' => $validated['abdomen_medida'],
         'quadril' => $validated['quadril'],
         'braco_relaxado_esquerdo' => $validated['braco_relaxado_esquerdo'],
         'braco_relaxado_direito' => $validated['braco_relaxado_direito'],
         'braco_contraido_esquerdo' => $validated['braco_contraido_esquerdo'],
         'braco_contraido_direito' => $validated['braco_contraido_direito'],
         'coxa_medial' => $validated['coxa_medial'],
         'panturrilha' => $validated['panturrilha'],

         // Dobras
         'peito' => $validated['peito'] ?? null,
         'triceps' => $validated['triceps'] ?? null,
         'subescapular' => $validated['subescapular'] ?? null,
         'axilar_media' => $validated['axilar_media'] ?? null,
         'supra_iliaca' => $validated['supra_iliaca'] ?? null,

         // corrigir nomes
         'abdomen_dobra' => $validated['abdomen'] ?? null,
         'coxa_dobra' => $validated['coxa'] ?? null,

         'protocolo' => $validated['protocolo'],
         'sexo_avaliacao' => $validated['sexo_avaliacao'],
         'observacoes' => $validated['observacoes'],
      ]);

      return redirect()
         ->route('avaliacao.index')
         ->with('success', 'Avaliação cadastrada com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $aluno = User::findOrFail($id);
      $avaliacoes = Avaliacao::where('aluno_id', $id)
         ->with('professor')
         ->orderBy('created_at', 'desc')
         ->get();
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
    * Show filter form for generating PDF.
    */
   public function filtroPdf($aluno_id)
   {
      $aluno = User::findOrFail($aluno_id);
      return view('avaliacao.filtro_pdf', compact('aluno'));
   }

   /**
    * Generate PDF for the specified evaluation with filters.
    */
   public function avaliacaoPdf(Request $request, $aluno_id)
   {
      $aluno = User::findOrFail($aluno_id);

      $query = Avaliacao::where('aluno_id', $aluno_id);

      // Aplicar filtros se fornecidos
      if ($request->has('dia') && $request->dia) {
         $query->whereDay('created_at', $request->dia);
      }

      if ($request->has('mes') && $request->mes) {
         $query->whereMonth('created_at', $request->mes);
      }

      if ($request->has('ano') && $request->ano) {
         $query->whereYear('created_at', $request->ano);
      }

      // Se não houver filtros, pegar todas as avaliações
      $avaliacoes = $query->with(['professor'])->orderBy('created_at', 'desc')->get();

      if ($avaliacoes->isEmpty()) {
         return back()->with('error', 'Nenhuma avaliação encontrada com os filtros selecionados.');
      }

      $pdf = Pdf::loadView('avaliacao.avaliacao_pdf_filtrado', [
         'aluno' => $aluno,
         'avaliacoes' => $avaliacoes,
         'filtros' => [
            'dia' => $request->dia,
            'mes' => $request->mes,
            'ano' => $request->ano,
         ]
      ])->setPaper('a4', 'portrait');

      $nomeArquivo = 'avaliacoes-' . $aluno->name;
      if ($request->ano) $nomeArquivo .= '-' . $request->ano;
      if ($request->mes) $nomeArquivo .= '-' . str_pad($request->mes, 2, '0', STR_PAD_LEFT);
      if ($request->dia) $nomeArquivo .= '-' . str_pad($request->dia, 2, '0', STR_PAD_LEFT);
      $nomeArquivo .= '.pdf';

      return $pdf->stream($nomeArquivo);
   }

   /**
    * View PDF for a specific evaluation.
    */
   public function viewPdf($id)
   {
      $avaliacao = Avaliacao::with(['aluno', 'professor'])->findOrFail($id);
      $aluno = $avaliacao->aluno;

      if (!$aluno) {
         return back()->with('error', 'Aluno não encontrado para esta avaliação.');
      }

      $pdf = Pdf::loadView('avaliacao.avaliacao_pdf', [
         'aluno' => $aluno,
         'u' => $avaliacao
      ])->setPaper('a4', 'portrait');

      return $pdf->stream('avaliacao-' . $aluno->name . '-' . $avaliacao->created_at->format('Y-m-d') . '.pdf');
   }

   /**
    * Show page to select evaluations for comparison.
    */
   public function comparacao($aluno_id)
   {
      $aluno = User::findOrFail($aluno_id);
      $avaliacoes = Avaliacao::where('aluno_id', $aluno_id)
         ->orderBy('created_at', 'desc')
         ->get();
      
      return view('avaliacao.comparacao', compact('aluno', 'avaliacoes'));
   }

   /**
    * Generate comparison PDF.
    */
   public function comparacaoPdf(Request $request, $aluno_id)
   {
      $aluno = User::findOrFail($aluno_id);
      
      $request->validate([
         'avaliacoes' => 'required|array|min:2',
         'avaliacoes.*' => 'exists:avaliacaos,id',
      ]);

      $avaliacoes = Avaliacao::whereIn('id', $request->avaliacoes)
         ->where('aluno_id', $aluno_id)
         ->with('professor')
         ->orderBy('created_at', 'asc')
         ->get();

      if ($avaliacoes->count() < 2) {
         return back()->with('error', 'Selecione pelo menos 2 avaliações para comparar.');
      }

      $pdf = Pdf::loadView('avaliacao.comparacao_pdf', [
         'aluno' => $aluno,
         'avaliacoes' => $avaliacoes
      ])->setPaper('a4', 'landscape');

      return $pdf->stream('comparacao-avaliacoes-' . $aluno->name . '.pdf');
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
