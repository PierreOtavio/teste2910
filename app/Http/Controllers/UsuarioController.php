<?php

namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;

    class UsuarioController extends Controller
    {
        public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orwhere('email', 'like', '%' . $request->search . '%')
                  ->orwhere('cpf', 'like', '%' . $request->search . '%');
                    
        }
        $users = $query->paginate(10);
    
        return view('teste.index', compact('users'));
    
    }
        public function create()
        {
            return view('teste.create');
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'cpf' => 'required|string|size:11|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'cargo' => 'required|in:0,1,2',
            ]);
            
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'cpf' => $request->cpf,
                'cargo' => $request->cargo,
            ]);

            return redirect()->route('teste.index')->with('success', 'Usuário criado com sucesso!');
        }

        public function show($id)
        {
            $user = User::findOrFail($id);
            return view('teste.show', compact('user'));
        }

        public function edit($id)
        {
            $user = User::findOrFail($id);
            

            return view('teste.edit', compact('user'));
        }

        public function update(Request $request, $id)
        {
            $user = User::findOrFail($id);

            $dadosValidados = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'cpf' => 'required|string|size:11',
                'cargo' => 'required|in:0,1,2',
            ]);

            $user->update($dadosValidados);
            return redirect()->route('teste.index')->with('success', 'Usuário atualizado com sucesso!');
        }

        public function destroy($id)
        {
            $user = User::findOrFail($id);

            $user->delete();
            return redirect()->route('teste.index')->with('success', 'Usuário excluído com sucesso!');
        }
        public function permissao(Request $request, User $user, $id) {

            $user = User::findOrFail($id);
            return view('teste.permissao', compact('user'));
            
        }
        
        

    }
