<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('perfil')->get();
        return view('user.index', compact('users'));
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
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

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
        $userEdit = User::findOrFail($id);
        return view('user.edit', compact('userEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userUpdate = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $userUpdate->update($validated);

        return redirect()->route('users')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userDestroy = User::findOrFail($id);
        $userDestroy->delete();

        return redirect()->route('user.index')->with('success', 'Usuário excluso com sucesso');
    }

    public function getData()
    {
        $users = User::select(['id', 'nome', 'email', 'created_at']);

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                return '
                    <a href="' . route('user.show', $user->id) . '" class="btn btn-primary btn-sm">Ver</a>
                    <a href="' . route('user.edit', $user->id) . '" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . $user->id . '">Excluir</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
