@extends('layouts.darkMode')

@section('title', 'Detalhes do Livro')

@section('content_header')
    <h1>Detalhes do Livro</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $livro->titulo }}</h3>
                <div class="card-tools">
                    <a href="{{ route('livros.index') }}" class="btn btn-secondary btn-sm">
                        Voltar para Listagem
                    </a>
                    <a href="{{ route('livros.edit', $livro->id) }}" class="btn btn-warning btn-sm">
                        Editar
                    </a>
                    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <p><strong>Descrição:</strong></p>
                <p>{{ $livro->descricao }}</p>

                <p><strong>Data de Publicação:</strong> {{ $livro->data_publicacao }}</p>
                <p><strong>Categoria:</strong> {{ $livro->categoria->nome }}</p>
            </div>
        </div>
    </div>
@endsection
