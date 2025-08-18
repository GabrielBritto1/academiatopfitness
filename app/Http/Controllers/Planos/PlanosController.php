<?php

namespace App\Http\Controllers\Planos;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use App\Models\Planos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanosController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $planos = Planos::with('beneficios')->get();
      $unidades = AcademiaUnidade::all();
      // $unidades->each(function ($unidade) {
      //    $unidade->modalidades = $unidade->modalidades()->get();
      // });
      $planosPorUnidade = [];
      foreach ($unidades as $unidade) {
         $planosPorUnidade[$unidade->nome] = $planos->filter(function ($plano) use ($unidade) {
            return $plano->unidades->contains('id', $unidade->id);
         });
      }
      $planosSemUnidade = $planos->filter(function ($plano) {
         return $plano->unidades->isEmpty();
      });
      if ($planosSemUnidade->isNotEmpty()) {
         $planosPorUnidade['Sem Unidade'] = $planosSemUnidade;
      }
      return view('planos.index', compact('planos', 'planosPorUnidade', 'unidades'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      return view('planos.index');
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'preco' => 'required|numeric',
         'color' => 'required|string|max:255',
         'beneficios' => 'sometimes|array',
         'beneficios.*.descricao' => 'required|string|max:255',
         'beneficios.*.ordem' => 'required|integer|min:0',
         'academia_unidade_id' => 'required|exists:academia_unidades,id',
      ]);

      DB::beginTransaction();

      try {
         // Criar o plano
         $plano = Planos::create([
            'name' => $validated['name'],
            'preco' => $validated['preco'],
            'color' => $validated['color'],
         ]);
         $plano->unidades()->attach($validated['academia_unidade_id']);

         // Adicionar benefícios se existirem
         if (isset($validated['beneficios'])) {
            foreach ($validated['beneficios'] as $beneficio) {
               $plano->beneficios()->create([
                  'beneficio' => $beneficio['descricao'],
                  'ordem' => $beneficio['ordem']
               ]);
            }
         }

         DB::commit();
         return redirect()->route('planos.index')->with('success', 'Plano criado com sucesso!');
      } catch (\Exception $e) {
         DB::rollBack();
         return redirect()->route('planos.index')->with('error', 'Não foi possível criar o plano!');
      }
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
      $planos = Planos::findOrFail($id);
      $unidades = AcademiaUnidade::all();
      // $unidades->each(function ($unidade) {
      //    $unidade->modalidades = $unidade->modalidades()->get();
      // });
      return view('planos.edit', compact('planos', 'unidades'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'preco' => 'required|numeric',
         'color' => 'required|string|max:255',
         'beneficios' => 'sometimes|array',
         'beneficios.*.descricao' => 'required|string|max:255',
         'beneficios.*.ordem' => 'required|integer|min:0',
         'academia_unidade_id' => 'required|exists:academia_unidades,id',
      ]);
      DB::beginTransaction();

      try {
         // Atualizar o plano
         $plano = Planos::findOrFail($id);

         $plano->update([
            'name' => $validated['name'],
            'preco' => $validated['preco'],
            'color' => $validated['color'],
         ]);
         $plano->unidades()->sync([$validated['academia_unidade_id']]);

         // Atualizar benefícios se existirem
         if (isset($validated['beneficios'])) {
            $plano->beneficios()->delete();
            foreach ($validated['beneficios'] as $beneficio) {
               $plano->beneficios()->create([
                  'beneficio' => $beneficio['descricao'],
                  'ordem' => $beneficio['ordem']
               ]);
            }
         }

         DB::commit();
         return redirect()->route('planos.index')->with('success', 'Plano atualizado com sucesso!');
      } catch (\Exception $e) {
         DB::rollBack();
         return redirect()->route('planos.index')->with('error', 'Não foi possível atualizar o plano!');
      }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      $planos = Planos::findOrFail($id);
      $planos->delete();

      return redirect()->route('planos.index')->with('success', 'Plano deletado com sucesso!');
   }

   public function carrinho()
   {
      $planos = Planos::all();
      $alunos = User::whereHas('roles', function ($query) {
         $query->where('name', 'aluno');
      })->get();
      $unidades = AcademiaUnidade::with('planos')->get();

      return view('carrinhodeplanos.index', compact('planos', 'alunos', 'unidades'));
   }
}
