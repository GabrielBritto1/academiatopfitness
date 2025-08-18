<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AcademiaUnidade;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
      $unidades = AcademiaUnidade::all();
      $unidades->each(function ($unidade) {
         $unidade->planos = $unidade->planos()->get();
      });
      $roles = Role::all();
      $professoresQuery = User::whereHas('roles', function ($query) {
         $query->where('name', 'professor');
      })->with(['planos']);

      if ($request->filled('search')) {
         $professoresQuery->where('name', 'like', '%' . $request->input('search') . '%');
      }
      if ($request->has('search')) {
         $professoresQuery->where('status', $request->input('status'));
      }
      $professores = $professoresQuery->paginate(10);

      return view('professores.index', compact('professores', 'roles', 'unidades'));
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
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users',
      ]);

      $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['email']),
      ]);
      $user->roles()->attach(3);

      return redirect()->route('professor.index')->with('success', 'Professor inserido com sucesso!');
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
      //
   }
}
