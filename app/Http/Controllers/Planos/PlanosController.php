<?php

namespace App\Http\Controllers\Planos;

use App\Http\Controllers\Controller;
use App\Models\Planos;
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
        return view('planos.index', compact('planos'));
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
            'preco_pre_vencimento' => 'required|numeric',
            'preco_pos_vencimento' => 'required|numeric',
            'dia_vencimento' => 'nullable|string|max:255',
            'color' => 'required|string|max:255',
            'beneficios' => 'sometimes|array',
            'beneficios.*.descricao' => 'required|string|max:255',
            'beneficios.*.ordem' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Criar o plano
            $plano = Planos::create([
                'name' => $validated['name'],
                'preco_pre_vencimento' => $validated['preco_pre_vencimento'],
                'preco_pos_vencimento' => $validated['preco_pos_vencimento'],
                'dia_vencimento' => $validated['dia_vencimento'] ?? null,
                'color' => $validated['color'],
            ]);

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
        $planos = Planos::findOrFail($id);
        $planos->delete();

        return redirect()->route('planos.index')->with('success', 'Plano deletado com sucesso!');
    }
}
