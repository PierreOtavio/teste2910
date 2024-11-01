<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('teste.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('teste.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        if ($request->has('role')) {
            $user->assingRole($request->role);
        }

        return redirect()->route('teste.index')->with('sucess', 'usuario criado com sucesso!');
    }


    public function show(User $user, $id)
    {
        $user = User::findOrFail($id);
        return view('teste.show', compact('user'));
    }


    public function edit(User $user, $id)
    {
        $user = User::findOrFail($id);
        return view('teste.edit', compact('user'));
    }

    public function update(Request $request, User $user, $id)
    {
        // dd($request->all());
        $user = User::findOrFail($id);
        $dadosValidados =  $request->validate([
            'name' => 'required|string|max: 255',
            'email' => 'required|email|max: 255',
            'cpf' => 'required|string|size:11',
            // 'senha' => 'nullable|string|min: 8|confirmed'
        ]);
        $user->update($dadosValidados);
        return redirect()->route('teste.index');
    }

    public function destroy(User $user, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('teste.index');
    }
    public function permissao(Request $request, User $user, $id) {}
}
