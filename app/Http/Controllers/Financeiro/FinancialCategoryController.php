<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinancialCategoryController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
      $type = $request->get('type');

      $query = FinancialCategory::query()
         ->withCount('transactions')
         ->orderBy('type')
         ->orderBy('name');

      if (in_array($type, ['receita', 'despesa'], true)) {
         $query->where('type', $type);
      } else {
         $type = null;
      }

      $categories = $query->get();
      $redirectTo = $request->get('redirect_to');

      return view('financeiro.categorias.index', compact('categories', 'type', 'redirectTo'));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $request->merge([
         'is_active' => $request->boolean('is_active'),
      ]);

      $validated = $request->validate([
         'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('financial_categories', 'name')->where(
               fn ($query) => $query->where('type', $request->input('type'))
            ),
         ],
         'type' => 'required|in:receita,despesa',
         'is_active' => 'boolean',
         'redirect_to' => 'nullable|string',
      ]);

      FinancialCategory::create([
         'name' => $validated['name'],
         'type' => $validated['type'],
         'is_active' => $validated['is_active'],
      ]);

      return $this->redirectAfterSave($request, 'Categoria criada com sucesso!', $validated['type']);
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $category = FinancialCategory::findOrFail($id);

      $request->merge([
         'is_active' => $request->boolean('is_active'),
      ]);

      $validated = $request->validate([
         'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('financial_categories', 'name')
               ->ignore($category->id)
               ->where(fn ($query) => $query->where('type', $request->input('type'))),
         ],
         'type' => 'required|in:receita,despesa',
         'is_active' => 'boolean',
         'redirect_to' => 'nullable|string',
      ]);

      $category->update([
         'name' => $validated['name'],
         'type' => $validated['type'],
         'is_active' => $validated['is_active'],
      ]);

      return $this->redirectAfterSave($request, 'Categoria atualizada com sucesso!', $validated['type']);
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $category = FinancialCategory::findOrFail($id);

      if ($category->transactions()->exists()) {
         return redirect()
            ->route('financeiro.categorias.index', ['type' => $category->type])
            ->with('error', 'Não é possível deletar uma categoria que já está em uso.');
      }

      $category->delete();

      return redirect()->route('financeiro.categorias.index')->with('success', 'Categoria deletada com sucesso!');
   }

   private function redirectAfterSave(Request $request, string $message, string $type)
   {
      $redirectTo = $request->input('redirect_to');

      if (is_string($redirectTo) && $this->isSafeInternalRedirect($redirectTo)) {
         return redirect($redirectTo)->with('success', $message);
      }

      return redirect()
         ->route('financeiro.categorias.index', ['type' => $type])
         ->with('success', $message);
   }

   private function isSafeInternalRedirect(string $redirectTo): bool
   {
      return str_starts_with($redirectTo, '/')
         || str_starts_with($redirectTo, url('/'));
   }
}
