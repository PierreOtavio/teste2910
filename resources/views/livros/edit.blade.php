@extends('layouts.darkMode')

@section('title', 'Editar Livro')

@section('content_header')
    <h1>Editar Livro</h1>
@stop

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Editar Livro</h3>
                <div class="card-tools">
                    <a href="{{ route('livros.index') }}" class="btn btn-secondary btn-sm">
                        Voltar para Listagem
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('livros.update', $livro->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" class="form-control" id="titulo"
                            value="{{ old('titulo', $livro->titulo) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" class="form-control" id="descricao">{{ old('descricao', $livro->descricao) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="data_publicacao">Data de Publicação</label>
                        <input type="date" name="data_publicacao" class="form-control" id="data_publicacao"
                            value="{{ old('data_publicacao', isset($livro) ? \Carbon\Carbon::createFromFormat('d/m/Y', $livro->data_publicacao)->format('Y-m-d') : '') }}"
                            required>
                    </div>


                    <div class="form-group">
                        <label for="categoria_id">Categoria</label>
                        <select name="categoria_id" class="form-control" id="categoria_id" required>
                            <option value="">Selecione a categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ $categoria->id == old('categoria_id', $livro->categoria_id) ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar Livro</button>
                </form>
            </div>
        </div>
    </div>
@endsection
