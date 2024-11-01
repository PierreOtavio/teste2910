<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    // Listar Autores
    public function index(Request $request)
    {
        // Filtro por nome e paginação
        $query = Autor::query();

        if ($request->has('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        // Paginação
        $autores = $query->paginate($request->input('perPage', 50));

        return view('autores.index', compact('autores'));
    }

    // Exibir o formulário de criação de autor
    public function create()
    {
        return view('autores.create');
    }

    // Armazenar novo autor
    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
        ]);

        // Criação do autor
        Autor::create($validated);

        // Redirecionar com mensagem de sucesso
        return redirect()->route('autores.index')->with('success', 'Autor adicionado com sucesso!');
    }

    // Exibir detalhes de um autor
    public function show($id)
    {
        $autor = Autor::findOrFail($id);
        return view('autores.show', compact('autor'));
    }

    // Exibir o formulário de edição de autor
    public function edit($id)
    {
        $autor = Autor::findOrFail($id);
        return view('autores.edit', compact('autor'));
    }

    // Atualizar um autor
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'data_nascimento' => 'nullable|date',
        ]);

        // Atualizar o autor
        $autor = Autor::findOrFail($id);
        $autor->update($validated);

        // Redirecionar com mensagem de sucesso
        return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso!');
    }

    // Excluir um autor
    public function destroy($id)
    {
        $autor = Autor::findOrFail($id);
        $autor->delete();

        // Redirecionar com mensagem de sucesso
        return redirect()->route('autores.index')->with('success', 'Autor excluído com sucesso!');
    }
}
