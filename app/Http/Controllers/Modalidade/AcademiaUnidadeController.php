<?php

namespace App\Http\Controllers\Modalidade;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
      $validated = $request->validate([
         'nome' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
         'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $data = $request->only(['nome', 'endereco']);

      if ($request->hasFile('logo')) {
         $logo = $request->file('logo');
         $nomeLogo = time() . '_' . $logo->getClientOriginalName();
         $logo->storeAs('public/unidades', $nomeLogo);
         $data['logo'] = 'unidades/' . $nomeLogo;
      }

      AcademiaUnidade::create($data);

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
      $unidade = AcademiaUnidade::findOrFail($id);
      return view('unidade.edit', compact('unidade'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $unidade = AcademiaUnidade::findOrFail($id);

      $validated = $request->validate([
         'nome' => 'required|string|max:255',
         'endereco' => 'required|string|max:255',
         'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $data = $request->only(['nome', 'endereco']);

      if ($request->hasFile('logo')) {
         // Deletar logo antigo se existir
         if ($unidade->logo && Storage::exists('public/' . $unidade->logo)) {
            Storage::delete('public/' . $unidade->logo);
         }

         $logo = $request->file('logo');
         $nomeLogo = time() . '_' . $logo->getClientOriginalName();
         $logo->storeAs('public/unidades', $nomeLogo);
         $data['logo'] = 'unidades/' . $nomeLogo;
      }

      $unidade->update($data);

      return redirect()->route('unidade.index')->with('success', 'Unidade atualizada com sucesso!');
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
