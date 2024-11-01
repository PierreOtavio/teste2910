@extends('layouts.darkMode')

@section('title', isset($autor) ? 'Editar Autor' : 'Adicionar Autor')

@section('content_header')
    <h1>{{ isset($autor) ? 'Editar Autor' : 'Adicionar Autor' }}</h1>
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
            <div class="card-body">
                <div class="card-header">
                    <h3 class="card-title">Editar Livro</h3>
                    <div class="card-tools">
                        <a href="{{ route('autores.index') }}" class="btn btn-secondary btn-sm">
                            Voltar para Listagem
                        </a>
                    </div>
                </div>
                <form action="{{ isset($autor) ? route('autores.update', $autor->id) : route('autores.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($autor))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control"
                            value="{{ old('nome', isset($autor) ? $autor->nome : '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="biografia">Biografia</label>
                        <textarea name="biografia" id="biografia" class="form-control">{{ old('biografia', isset($autor) ? $autor->biografia : '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" class="form-control"
                            value="{{ old('data_nascimento', isset($autor) ? \Carbon\Carbon::parse($autor->data_nascimento)->format('Y-m-d') : '') }}">

                    </div>

                    <button type="submit" class="btn btn-primary">{{ isset($autor) ? 'Atualizar' : 'Adicionar' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
