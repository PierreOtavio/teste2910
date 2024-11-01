<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Exibir a lista de livros com paginação.
     */
    public function index(Request $request)
    {
        $titulo = $request->get('titulo'); // Filtro opcional por título
        $perPage = $request->get('perPage', 10); // Quantidade de itens por página

        $livros = Livro::with('categoria')
            ->filtrarPorTitulo($titulo)
            ->paginate($perPage);

        return view('livros.index', compact('livros'));
    }

    /**
     * Exibir o formulário para criar um novo livro.
     */
    public function create()
    {
        $categorias = Categoria::all(); // Buscar todas as categorias para o dropdown
        return view('livros.create', compact('categorias'));
    }

    /**
     * Armazenar um novo livro no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_publicacao' => 'required|date',
            'categoria_id' => 'required|exists:categorias,id', // Validar existência da categoria
        ]);

        Livro::create($request->all()); // Criar livro com os dados validados

        return redirect()->route('livros.index')->with('success', 'Livro criado com sucesso!');
    }

    /**
     * Exibir um livro específico.
     */
    public function show($id)
    {
        $livro = Livro::with('categoria')->findOrFail($id); // Carregar livro junto com a categoria
        return view('livros.show', compact('livro'));
    }

    /**
     * Exibir o formulário para editar um livro existente.
     */
    public function edit($id)
    {
        $livro = Livro::findOrFail($id); // Buscar livro por ID
        $categorias = Categoria::all(); // Buscar todas as categorias para o dropdown

        return view('livros.edit', compact('livro', 'categorias'));
    }

    /**
     * Atualizar um livro existente no banco de dados.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_publicacao' => 'required|date',
            'categoria_id' => 'required|exists:categorias,id', // Validar existência da categoria
        ]);

        $livro = Livro::findOrFail($id); // Buscar livro por ID
        $livro->update($request->all()); // Atualizar com os dados validados

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Excluir um livro do banco de dados.
     */
    public function destroy($id)
    {
        $livro = Livro::findOrFail($id); // Buscar livro por ID
        $livro->delete(); // Excluir livro

        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
    }
}
