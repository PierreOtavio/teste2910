@extends('layouts.darkMode')

@section('title', 'Detalhes do Autor')

@section('content_header')
    <h1>Detalhes do Autor</h1>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>{{ $autor->nome }}</h3>
                <p><strong>Data de Nascimento:</strong> {{ $autor->data_nascimento }}</p>
                <p><strong>Biografia:</strong> {{ $autor->biografia }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('autores.edit', $autor->id) }}" class="btn btn-warning">Editar</a>
                <form action="{{ route('autores.destroy', $autor->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            </div>
        </div>
    </div>
@endsection
