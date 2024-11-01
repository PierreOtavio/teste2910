@extends('layouts.darkMode')

@section('title', 'Detalhes da Categoria')

@section('content_header')
    <h1>Detalhes da Categoria</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Detalhes da Categoria
            </div>
            <div class="card-body">
                <h5 class="card-title">Nome:</h5>
                <p class="card-text">{{ $categoria->nome }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Voltar</a>
                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            </div>
        </div>
    </div>
@endsection
