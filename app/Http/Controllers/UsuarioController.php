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
        $roles = Role::all(); //PERGUNTAR OTÁVIO

        return view('teste.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,editor,viewer', // Validação do cargo
        ]);

        if (!auth()->user()->hasPermission('create')) {
            return redirect('/users')->with('error', 'Você não tem permissão para criar usuários.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Criptografa a senha
            'role' => $request->role, // Armazena o cargo selecionado (perresla kk)
        ]);

        return redirect()->route('teste.index')->with('sucess', 'Usuário criado com sucesso!');
    }


    public function show(User $user, $id)
    {
        $user = User::findOrFail($id);
        return view('teste.show', compact('user'));
    }


    public function edit(User $user, $id)
    {
        $user = User::findOrFail($id);
        if (!auth()->user()->hasPermission('edit')) {
            return redirect('/users')->with('error', 'Você não tem permissão para criar usuários.');
        }
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
            'role' => 'required|string|in:admin,editor,viewer',
            // 'senha' => 'nullable|string|min: 8|confirmed'
        ]);
        if (!auth()->user()->hasPermission('edit')) {
            return redirect('/users')->with('error', 'Você não tem permissão para atualizar usuários.');
        }
        $user->update($dadosValidados);
        return redirect()->route('teste.index');
    }

    public function destroy(User $user, $id)
    {
        if (!auth()->user()->hasPermission('delete')) {
            return redirect('/users')->with('error', 'Você não tem permissão para excluir usuários.');
        }
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('teste.index');
    }

    public function permissao(Request $request, User $user, $id) 
    {
        
    }
}
