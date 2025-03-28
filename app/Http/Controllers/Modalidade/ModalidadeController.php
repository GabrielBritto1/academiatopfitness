<?php

namespace App\Http\Controllers\Modalidade;

use App\Http\Controllers\Controller;
use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Modalidade::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
            if ($request->input('status') !== null) {
                $query->where('status', $request->input('status'));
            }
        }

        $modalidades = $query->paginate(10);
        return view('modalidade.index', compact('modalidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modalidade.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'duracao' => 'required|string|max:255',
            'nivel_dificuldade' => 'required|string|max:255',
        ]);

        Modalidade::create([
            'name' => $validated['name'],
            'descricao' => $validated['descricao'],
            'duracao' => $validated['duracao'],
            'nivel_dificuldade' => $validated['nivel_dificuldade'],
        ]);

        return redirect()->route('modalidade.index')->with('success', 'Modalidade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $modalidadeShow = Modalidade::findOrFail($id);
        return view('modalidade.show', compact('modalidadeShow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $modalidadeEdit = Modalidade::findOrFail($id);
        return view('modalidade.edit', compact('modalidadeEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'duracao' => 'required|string|max:255',
            'nivel_dificuldade' => 'required|string|max:255',
            'status' => 'required',
        ]);

        Modalidade::find($id)->update([
            'name' => $validated['name'],
            'descricao' => $validated['descricao'],
            'duracao' => $validated['duracao'],
            'nivel_dificuldade' => $validated['nivel_dificuldade'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('modalidade.index')->with('success', 'Modalidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $modalidadeDestroy = Modalidade::findOrFail($id);
        $modalidadeDestroy->delete();

        return redirect()->route('modalidade.index')->with('success', 'Modalidade deletada com sucesso!');
    }

    public function ativador(string $id)
    {
        $modalidadeAtivador = Modalidade::findOrFail($id);
        $modalidadeAtivador->status = !$modalidadeAtivador->status;
        $modalidadeAtivador->save();

        return redirect()->route('modalidade.index')->with('success', 'Status da modalidade alterado com sucesso!');
    }
}
