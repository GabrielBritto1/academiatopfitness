<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlunoController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $unidades = AcademiaUnidade::all();
      $unidades->each(function ($unidade) {
         $unidade->planos = $unidade->planos()->get();
      });
      $roles = Role::all();
      $alunos = User::whereHas('roles', function ($query) {
         $query->where('name', 'aluno');
      })->with(['planos'])
         ->paginate(10);

      return view('alunos.index', compact('alunos', 'roles', 'unidades'));
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      return view('aluno.index');
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users',
         'password' => 'required|string|min:8|confirmed',
         'academia_unidade_id' => 'required|exists:academia_unidades,id',
         'plano_id' => 'required|exists:planos,id',
      ]);

      $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['password']),
      ]);
      $user->roles()->attach(2);

      $user->planos()->attach($validated['plano_id'], [
         'academia_unidade_id' => $validated['academia_unidade_id']
      ]);

      return redirect()->route('aluno.index')->with('success', 'Aluno inserido com sucesso!');
   }

   /**
    * Display the specified resource.
    */
   public function show(string $id)
   {
      $aluno = User::findOrFail($id);
      return view('alunos.show', compact('aluno'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(string $id)
   {
      $aluno = User::findOrFail($id);
      return view('alunos.edit', compact('aluno'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, string $id)
   {
      $user = User::findOrFail($id);

      $validated = $request->validate([
         'name' => 'required',
         'email' => 'required|email',
      ]);

      $user->update([
         'name' => $validated['name'],
         'email' => $validated['email'],
      ]);

      return redirect()->route('aluno.index')->with('success', 'Aluno editado com sucesso!');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(string $id)
   {
      //
   }

   public function toggleStatus(string $id)
   {
      $user = User::findOrFail($id);
      $user->status = !$user->status;
      $user->save();

      return redirect()->route('aluno.index')->with('success', 'Status do aluno atualizado com sucesso!');
   }
}
