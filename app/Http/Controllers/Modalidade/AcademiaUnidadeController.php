<?php

namespace App\Http\Controllers\Modalidade;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use Illuminate\Http\Request;

class AcademiaUnidadeController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $unidades = AcademiaUnidade::all();
      return view('unidade.index', compact('unidades'));
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
      $request->validate([
         'nome' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
      ]);

      AcademiaUnidade::create($request->all());

      return redirect()->route('unidade.index')->with('success', 'Unidade criada com sucesso!');
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
      $unidades = AcademiaUnidade::findOrFail($id);
      return view('unidade.edit', compact('unidades'));
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
      //
   }

   // public function modalidades($id)
   // {
   //    $unidade = AcademiaUnidade::with('modalidades')->findOrFail($id);
   //    $modalidades = $unidade->modalidades;
   //    return view('unidade.modalidadesUnidade', compact('unidade', 'modalidades'));
   // }
}
