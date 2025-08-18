<?php

namespace App\Http\Controllers;

use App\Models\AcademiaUnidade;
use App\Models\PlanilhaTreino;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PlanilhaTreinoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $planilhas = PlanilhaTreino::all();
      return view('planilha-treino.index', compact('planilhas'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      //
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      //
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
      $planilha = PlanilhaTreino::findOrFail($id);
      $unidades = AcademiaUnidade::all();
      return view('planilha-treino.edit', compact('planilha', 'unidades'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $planilha = PlanilhaTreino::findOrFail($id);
      $validated = $request->validate([
         'unidade_id' => 'required',
         'professor_id' => 'required',
         'aluno_id' => 'required',
         'plano_id' => 'required',
      ]);
      $planilha->update([
         'unidade_id' => $validated['unidade_id'],
         'professor_id' => $request['professor_id'],
         'aluno_id' => $request['aluno_id'],
         'plano_id' => $request['plano_id']
      ]);
      return redirect()->route('planilha-treino.index')->with('success', 'Planilha editada com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      //
   }
   public function planilhaTreinoPdf(string $id)
   {
      $planilha =  PlanilhaTreino::findOrFail($id);
      $aluno = $planilha->aluno;
      $professor = $planilha->professor;
      $pdf = Pdf::loadView('planilha-treino.planilha_treino_pdf', compact('planilha', 'aluno', 'professor'))->setPaper('A4');
      return $pdf->stream('planilha-treino.pdf');
   }
}
