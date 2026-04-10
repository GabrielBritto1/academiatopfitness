<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;

class FinancialCategoryController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $categories = FinancialCategory::orderBy('type')->orderBy('name')->get();
      return view('financeiro.categorias.index', compact('categories'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'type' => 'required|in:receita,despesa',
         'is_active' => 'boolean',
      ]);

      FinancialCategory::create([
         'name' => $validated['name'],
         'type' => $validated['type'],
         'is_active' => $request->has('is_active') ? true : false,
      ]);

      return redirect()->route('financeiro.categorias.index')->with('success', 'Categoria criada com sucesso!');
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $category = FinancialCategory::findOrFail($id);

      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'type' => 'required|in:receita,despesa',
         'is_active' => 'boolean',
      ]);

      $category->update([
         'name' => $validated['name'],
         'type' => $validated['type'],
         'is_active' => $request->has('is_active') ? true : false,
      ]);

      return redirect()->route('financeiro.categorias.index')->with('success', 'Categoria atualizada com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $category = FinancialCategory::findOrFail($id);
      $category->delete();

      return redirect()->route('financeiro.categorias.index')->with('success', 'Categoria deletada com sucesso!');
   }
}
