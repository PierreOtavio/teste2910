@extends('layouts.darkMode')

@section('title', 'Livros')

@section('content_header')
    <h1>Listagem de Livros</h1>
@stop

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Livros</h3>
                <div class="card-tools">
                    <a href="{{ route('livros.create') }}" class="btn btn-success btn-sm">
                        Adicionar Livro
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário de Filtro -->
                <form method="GET" action="{{ route('livros.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="titulo">Título:</label>
                                <input type="text" name="titulo" id="titulo" class="form-control"
                                    placeholder="Filtrar por título" value="{{ request('titulo') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="perPage">Itens por Página:</label>
                                <input type="number" name="perPage" id="perPage" class="form-control"
                                    value="{{ request('perPage', 10) }}" min="1" max="100">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-4">
                            <div class="w-100 text-center">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>


                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>Data de Publicação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($livros as $livro)
                            <tr>
                                <td>{{ $livro->id }}</td>
                                <td>{{ $livro->titulo }}</td>
                                <td>{{ $livro->categoria->nome }}</td>
                                <td>{{ $livro->data_publicacao }}</td>
                                <td>
                                    <a href="{{ route('livros.show', $livro->id) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('livros.edit', $livro->id) }}"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Renderizando os links de paginação -->
                <div class="d-flex justify-content-center m-3">
                    {{ $livros->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
