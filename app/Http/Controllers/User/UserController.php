<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perfils = Perfil::all();
        $users = User::with('perfil')->get();
        return view('user.index', compact('users', 'perfils'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.index');
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
            'perfil' => 'required|exists:perfils,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->perfil()->attach($validated['perfil']);

        return redirect()->route('user.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userShow = User::find($id);
        return view('user.show', compact('userShow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perfils = Perfil::all();
        $userEdit = User::findOrFail($id);
        return view('user.edit', compact('userEdit', 'perfils'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userUpdate = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'perfil' => 'required|exists:perfils,id',
        ]);

        $userUpdate->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        $userUpdate->perfil()->attach($validated['perfil']);

        return redirect()->route('user.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userDestroy = User::findOrFail($id);
        $userDestroy->delete();

        return redirect()->route('user.index')->with('success', 'Usuário deletado com sucesso');
    }
}
