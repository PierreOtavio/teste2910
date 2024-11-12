@extends('layouts.darkMode')
@section('content_header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <h1>Usuários</h1>
    @if (auth()->user()->cargo == 0) 
        <a class="btn btn-novo" href="{{ route('teste.create') }}">Novo Usuário</a>

        <form action="{{ route('teste.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar usuário" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @else
        <form action="{{ route('teste.index') }}" method="GET">
            <input class="btn btn-novo" name="search" placeholder="Buscar usuário" value="{{ request('search') }}">
            <button type="submit" class="btn btn-novo">Buscar</button>
        </form>
    @endif

@stop

@section('content')
   <div class="content">
    @if (session('sucess'))
        <div class="alert alert-success" role="alert">
           {{ session('sucess') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Nome:</th>
                <th>E-mail:</th>
                <th>CPF:</th>
                <th>Permissões:</th>
                <th>Gerenciamento:</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->cpf }}</td>
                    <td>
                        <a href="{{ route('teste.permissao', $user->id) }}"> <i class="fas fa-user"></i> </a>
                    </td>
                    <td>
                        @if (auth()->user()->cargo == 0) 
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('teste.edit', $user->id) }}" class="btn btn-info btn-sm">Editar</a>
                            <form action="{{ route('teste.destroy', $user->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Certeza que deseja excluir?')">Excluir</button>
                            </form>
                        @else
                            <a href="{{ route('teste.show', $user->id) }}" class="btn btn-info btn-sm">Ver</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
