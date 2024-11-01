@extends('layouts.darkMode')

@section('title', 'Categorias')

@section('content_header')
    <h1>Listagem de Categorias</h1>
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
                <h3 class="card-title">Categorias</h3>
                <div class="card-tools">
                    <a href="{{ route('categorias.create') }}" class="btn btn-success btn-sm">
                        Adicionar Categoria
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Formulário de Filtro -->
                <form method="GET" action="{{ route('categorias.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" name="nome" id="nome" class="form-control" placeholder="Filtrar por nome" value="{{ request('nome') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="perPage">Itens por Página:</label>
                                <input type="number" name="perPage" id="perPage" class="form-control" value="{{ request('perPage', 10) }}" min="1" max="100">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-4">
                            <div class="w-100 text-center">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Tabela de Categorias -->
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>{{ $categoria->nome }}</td>
                            <td>
                                <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-info btn-sm">Ver</a>
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Renderizando os links de paginação -->
                <div class="d-flex justify-content-center m-3">
                    {{ $categorias->links('vendor.pagination.bootstrap-4') }} <!-- Ou 'vendor.pagination.bootstrap-5', dependendo da versão -->
                </div>
            </div>
        </div>
    </div>
@endsection
